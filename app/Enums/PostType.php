<?php

namespace App\Enums;

enum PostType: string
{
    case Text = 'text';
    case Image = 'image';
    case Video = 'video';
    case Carousel = 'carousel';
}
