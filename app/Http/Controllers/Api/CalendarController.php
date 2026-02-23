<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateDayRequest;
use App\Jobs\GenerateCalendarDayJob;
use App\Models\BusinessAccount;
use App\Support\ApiResponse;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'view', $business);

        $request->validate([
            'month' => ['sometimes', 'date_format:Y-m'],
            'from' => ['sometimes', 'date_format:Y-m-d'],
            'to' => ['sometimes', 'date_format:Y-m-d'],
        ]);

        $timezone = $business->timezone ?? 'UTC';

        if ($request->filled('month')) {
            $start = Carbon::parse($request->input('month').'-01', $timezone)->startOfMonth();
            $end = $start->copy()->endOfMonth();
        } elseif ($request->filled('from') && $request->filled('to')) {
            $start = Carbon::parse($request->input('from'), $timezone)->startOfDay();
            $end = Carbon::parse($request->input('to'), $timezone)->endOfDay();
        } else {
            $start = Carbon::now($timezone)->startOfMonth();
            $end = $start->copy()->endOfMonth();
        }

        $posts = $business->posts()
            ->whereBetween('scheduled_at', [$start, $end])
            ->select('scheduled_at', 'status')
            ->get();

        $days = [];
        foreach ($posts as $post) {
            $date = Carbon::parse($post->scheduled_at, $timezone)->toDateString();
            if (! isset($days[$date])) {
                $days[$date] = ['total' => 0, 'scheduled' => 0, 'published' => 0, 'failed' => 0];
            }
            $days[$date]['total']++;
            if ($post->status === 'scheduled') {
                $days[$date]['scheduled']++;
            } elseif ($post->status === 'published') {
                $days[$date]['published']++;
            } elseif ($post->status === 'failed') {
                $days[$date]['failed']++;
            }
        }

        return ApiResponse::success([
            'days' => $days,
            'posts_per_day' => max(1, (int) ($business->posts_per_day ?? 1)),
        ]);
    }

    public function day(Request $request, BusinessAccount $business): JsonResponse
    {
        $this->authorizeForUser($request->user(), 'view', $business);

        $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
        ]);

        $dateStr = $request->input('date');
        $timezone = $business->timezone ?? 'UTC';
        $start = Carbon::parse($dateStr, $timezone)->startOfDay();
        $end = $start->copy()->endOfDay();

        $posts = $business->posts()
            ->whereBetween('scheduled_at', [$start, $end])
            ->with('postMetric')
            ->orderBy('scheduled_at')
            ->get();

        $summary = [
            'total' => $posts->count(),
            'scheduled' => $posts->where('status', 'scheduled')->count(),
            'published' => $posts->where('status', 'published')->count(),
            'failed' => $posts->where('status', 'failed')->count(),
        ];

        $engagement = [
            'reach' => 0,
            'impressions' => 0,
            'likes' => 0,
            'comments' => 0,
            'shares' => 0,
            'saves' => 0,
            'engagement_rate' => 0.0,
        ];

        foreach ($posts as $post) {
            $metric = $post->postMetric;
            if ($metric) {
                $engagement['reach'] += $metric->reach;
                $engagement['impressions'] += $metric->impressions;
                $engagement['likes'] += $metric->likes;
                $engagement['comments'] += $metric->comments;
                $engagement['shares'] += $metric->shares;
                $engagement['saves'] += $metric->saves;
            }
        }

        if ($engagement['reach'] > 0) {
            $totalEngagement = $engagement['likes'] + $engagement['comments'] + $engagement['shares'] + $engagement['saves'];
            $engagement['engagement_rate'] = round(($totalEngagement / $engagement['reach']) * 100, 2);
        }

        $postsData = $posts->map(function ($post) {
            $metric = $post->postMetric;

            return [
                'id' => $post->id,
                'caption' => $post->caption ? mb_substr($post->caption, 0, 150).(mb_strlen($post->caption) > 150 ? '…' : '') : '',
                'status' => $post->status,
                'scheduled_at' => $post->scheduled_at?->toIso8601String(),
                'quality_score' => $post->quality_score,
                'metrics' => $metric ? [
                    'reach' => $metric->reach,
                    'impressions' => $metric->impressions,
                    'likes' => $metric->likes,
                    'comments' => $metric->comments,
                    'shares' => $metric->shares,
                    'saves' => $metric->saves,
                    'engagement_rate' => $metric->engagement_rate,
                ] : null,
            ];
        });

        return ApiResponse::success([
            'date' => $dateStr,
            'summary' => $summary,
            'engagement' => $engagement,
            'posts' => $postsData,
        ]);
    }

    public function generateDay(GenerateDayRequest $request, BusinessAccount $business): JsonResponse
    {
        $dateStr = Carbon::parse($request->input('date'))->toDateString();
        $count = $request->input('count');
        $targetCount = $count ?? max(1, (int) ($business->posts_per_day ?? 1));
        $targetCount = max(1, min(20, $targetCount));

        $existingCount = $business->posts()
            ->whereDate('scheduled_at', $dateStr)
            ->count();

        if ($existingCount >= $targetCount) {
            return ApiResponse::success(null, 'Already generated for this date.', null, 200);
        }

        GenerateCalendarDayJob::dispatch($business->id, $dateStr, $count);

        return ApiResponse::success(null, 'Generation started.', null, 202);
    }
}
