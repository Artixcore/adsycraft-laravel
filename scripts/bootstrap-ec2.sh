#!/usr/bin/env bash
#
# Bootstrap EC2 (Ubuntu 22.04) for Adsycraft Laravel app.
# Run as root or with sudo: sudo bash scripts/bootstrap-ec2.sh
#
# Installs: Nginx, PHP 8.2, Composer, and systemd units for queue worker and scheduler.
# After running, create /var/www/adsycraft/.env from docs/deployment.md section 5.
#

set -e

APP_USER="${APP_USER:-www-data}"
APP_GROUP="${APP_GROUP:-www-data}"
APP_ROOT="${APP_ROOT:-/var/www/adsycraft}"

echo "[bootstrap] Updating apt..."
apt-get update -qq

echo "[bootstrap] Installing Nginx..."
apt-get install -y -qq nginx

echo "[bootstrap] Adding Ondrej PHP PPA and installing PHP 8.2..."
apt-get install -y -qq software-properties-common
add-apt-repository -y ppa:ondrej/php
apt-get update -qq
apt-get install -y -qq \
  php8.2-fpm \
  php8.2-cli \
  php8.2-mysql \
  php8.2-xml \
  php8.2-mbstring \
  php8.2-curl \
  php8.2-zip \
  php8.2-redis \
  php8.2-bcmath \
  php8.2-intl

echo "[bootstrap] Installing Composer..."
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

echo "[bootstrap] Creating app directory ${APP_ROOT}..."
mkdir -p "${APP_ROOT}"
chown "${APP_USER}:${APP_GROUP}" "${APP_ROOT}" || true

echo "[bootstrap] Configuring Nginx for Laravel..."
cat > /etc/nginx/sites-available/adsycraft << 'NGINX_EOF'
server {
    listen 80;
    listen [::]:80;
    server_name _;
    root /var/www/adsycraft/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
        fastcgi_read_timeout 300;
    }
}
NGINX_EOF

ln -sf /etc/nginx/sites-available/adsycraft /etc/nginx/sites-enabled/
rm -f /etc/nginx/sites-enabled/default
nginx -t && systemctl reload nginx

echo "[bootstrap] Creating systemd unit for queue worker..."
cat > /etc/systemd/system/adsycraft-queue.service << 'QUEUE_EOF'
[Unit]
Description=Adsycraft Laravel Queue Worker
After=network.target

[Service]
Type=simple
User=www-data
Group=www-data
Restart=always
RestartSec=3
WorkingDirectory=/var/www/adsycraft
ExecStart=/usr/bin/php /var/www/adsycraft/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
Environment=HOME=/var/www/adsycraft

[Install]
WantedBy=multi-user.target
QUEUE_EOF

echo "[bootstrap] Creating systemd unit for scheduler..."
cat > /etc/systemd/system/adsycraft-scheduler.service << 'SCHED_EOF'
[Unit]
Description=Adsycraft Laravel Scheduler
After=network.target

[Service]
Type=simple
User=www-data
Group=www-data
Restart=always
RestartSec=3
WorkingDirectory=/var/www/adsycraft
ExecStart=/usr/bin/php /var/www/adsycraft/artisan schedule:work
Environment=HOME=/var/www/adsycraft

[Install]
WantedBy=multi-user.target
SCHED_EOF

systemctl daemon-reload
echo "[bootstrap] Enable and start queue/scheduler after first deploy:"
echo "  sudo systemctl enable --now adsycraft-queue adsycraft-scheduler"

echo "[bootstrap] Done. Next steps:"
echo "  1. Deploy application code via CI/CD (push to main) or rsync manually."
echo "  2. Create ${APP_ROOT}/.env with production values (see docs/deployment.md section 5)."
echo "  3. Run: cd ${APP_ROOT} && php artisan key:generate && php artisan migrate --force"
echo "  4. Enable services: sudo systemctl enable --now adsycraft-queue adsycraft-scheduler"
