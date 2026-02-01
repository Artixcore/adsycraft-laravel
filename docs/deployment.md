# Adsycraft Deployment Guide

CI/CD pipeline for Adsycraft (Laravel) on AWS: EC2, S3, MySQL RDS, and ElastiCache Redis.

---

## 1. AWS resources to provision

Provision these resources (manually in AWS Console or via IaC such as Terraform/CDK in an `infra/` directory).

| Resource | Purpose |
|----------|---------|
| **VPC** | Default or custom VPC. Place EC2, RDS, and ElastiCache in the same VPC (or peered) for private connectivity. |
| **EC2** | Ubuntu 22.04. Nginx, PHP 8.2, Composer. App root e.g. `/var/www/adsycraft`. Run queue worker and scheduler (systemd or supervisor). |
| **RDS MySQL** | Engine MySQL 8.x. Private subnet. Security group allows inbound 3306 from EC2 security group only. |
| **ElastiCache Redis** | Redis 7.x. Private subnet. Security group allows inbound 6379 from EC2 security group only. |
| **S3 bucket (media)** | Laravel `FILESYSTEM_DISK=s3`; set `AWS_BUCKET` in production `.env`. |
| **S3 bucket (artifacts)** | Optional. For deploy-via-S3: GitHub Actions uploads tarball; EC2 pulls and extracts. |
| **IAM** | EC2 instance profile (e.g. S3 read for artifact pull). Or use GitHub OIDC + IAM role for deploy. |
| **Secrets** | RDS credentials, Redis endpoint, S3 bucket name (and keys if not using IAM). Prefer AWS Systems Manager Parameter Store or Secrets Manager; otherwise inject into EC2 `.env` at deploy. |

### Security groups (summary)

- **EC2 SG:** Allow 22 (SSH) from your IP or CI; 80/443 from ALB or internet if no ALB.
- **RDS SG:** Allow 3306 from EC2 SG only.
- **ElastiCache SG:** Allow 6379 from EC2 SG only.

### Network

- EC2 in a public or private subnet (with NAT if private).
- RDS and ElastiCache in private subnets; no public access.

---

## 2. Bootstrap EC2

On a fresh EC2 instance (Ubuntu 22.04), run the bootstrap script once to install Nginx, PHP 8.2, Composer, and systemd units for queue and scheduler.

See [scripts/bootstrap-ec2.sh](scripts/bootstrap-ec2.sh). Run as root or with sudo:

```bash
sudo bash scripts/bootstrap-ec2.sh
```

Then create `/var/www/adsycraft/.env` from the production env template (see section 5) and set permissions. Application code is deployed by CI/CD (see section 4).

---

## 3. GitHub Actions CI/CD

- **CI:** On push/PR to `main`, the workflow runs tests (PHP 8.2, MySQL + Redis services), builds frontend assets, and uploads `public/build` as an artifact.
- **CD:** On push to `main`, the deploy job runs only if tests pass. It checks out the repo, downloads the build artifact, and deploys to EC2 via SSH + rsync.

### Required GitHub secrets (SSH deploy)

| Secret | Description |
|--------|-------------|
| `EC2_HOST` | EC2 instance hostname or IP. |
| `EC2_SSH_KEY` | Private key for SSH (e.g. `-----BEGIN ... END ...-----`). |
| `EC2_USER` | Optional. SSH user (default `ubuntu` for Ubuntu AMI). |

Production env vars (RDS, Redis, S3, `APP_KEY`, etc.) must **not** be stored in GitHub; they live on the EC2 `.env` only.

---

## 4. Deploy job behaviour

The deploy job (runs on `main` after tests pass):

1. Checks out the repository.
2. Downloads the `build` artifact and restores `public/build`.
3. Runs `composer install --no-dev --optimize-autoloader` on the runner (for a complete tree to rsync).
4. Rsyncs the app to EC2 at `EC2_HOST` (path `/var/www/adsycraft`), excluding `.git`, `node_modules`, and dev files.
5. Runs post-deploy commands on EC2 over SSH:
   - `composer install --no-dev --optimize-autoloader`
   - `php artisan migrate --force`
   - `php artisan config:cache` and `php artisan route:cache`
   - `php artisan queue:restart`
   - `php artisan storage:link` (if using local/s3 mix)

Ensure the queue worker and scheduler are running on EC2 (e.g. via the bootstrap script’s systemd units or supervisor).

---

## 5. Production `.env` on EC2

Configure `/var/www/adsycraft/.env` on the server. Required variables:

**App**

- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://your-domain.com`
- `APP_KEY=` (generate with `php artisan key:generate`)

**Database (RDS MySQL)**

- `DB_CONNECTION=mysql`
- `DB_HOST=<RDS endpoint>`
- `DB_PORT=3306`
- `DB_DATABASE=adsycraft`
- `DB_USERNAME=`
- `DB_PASSWORD=`

**Redis (ElastiCache)**

- `REDIS_HOST=<ElastiCache endpoint>`
- `REDIS_PASSWORD=` (if ElastiCache auth is enabled)
- `REDIS_PORT=6379`
- `REDIS_CLIENT=phpredis`

**Cache, queue, session**

- `CACHE_STORE=redis`
- `QUEUE_CONNECTION=redis`
- `SESSION_DRIVER=redis`

**S3 (app media)**

- `FILESYSTEM_DISK=s3`
- `AWS_ACCESS_KEY_ID=`
- `AWS_SECRET_ACCESS_KEY=`
- `AWS_DEFAULT_REGION=us-east-1`
- `AWS_BUCKET=your-media-bucket`

**Other**

- Set `LOG_*`, `MAIL_*`, `META_*`, and any other app-specific vars as needed.

Use a copy of `.env.example` and fill in production values; never commit production `.env` or secrets to the repo.
