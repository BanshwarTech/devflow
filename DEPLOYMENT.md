# DevFlow Deployment Guide

## Prerequisites

- Server with PHP 8.3+, Composer, Node.js, Nginx/Apache
- MySQL 8.0+ or PostgreSQL
- Redis (recommended for production)
- Supervisor (for queue workers)

## Environment Setup

1. **Clone repository**
```bash
git clone <repository-url> /var/www/devflow
cd /var/www/devflow
```

2. **Install dependencies**
```bash
composer install --no-dev --optimize-autoloader
npm install && npm run build
```

3. **Environment configuration**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure `.env` for production**
```env
APP_NAME=DevFlow
APP_ENV=production
APP_DEBUG=false
APP_URL=https://devflow.yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=devflow
DB_USERNAME=devflow
DB_PASSWORD=secure_password

BROADCAST_CONNECTION=reverb
QUEUE_CONNECTION=redis
CACHE_STORE=redis
SESSION_DRIVER=redis

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@devflow.yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

5. **Storage permissions**
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## Database Setup

```bash
php artisan migrate --force
php artisan db:seed --force
```

## Caching

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Queue Worker

Create `/etc/supervisor/conf.d/devflow-worker.conf`:
```ini
[program:devflow-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/devflow/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/supervisor/devflow-worker.log
stopwaitsecs=3600
```

Start supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start devflow-worker:*
```

## Reverb Server

Install Reverb server:
```bash
composer require laravel/reverb
```

Run Reverb server (separate process):
```bash
php artisan reverb:start
```

For production, use Supervisor to keep Reverb running:
```ini
[program:devflow-reverb]
command=php /var/www/devflow/artisan reverb:start
autostart=true
autorestart=true
user=www-data
```

## Nginx Configuration

Create `/etc/nginx/sites-available/devflow`:
```nginx
server {
    listen 80;
    server_name devflow.yourdomain.com;
    root /var/www/devflow/public;

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
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        access_log off;
    }
}
```

Enable site:
```bash
ln -s /etc/nginx/sites-available/devflow /etc/nginx/sites-enabled/
nginx -t
systemctl reload nginx
```

## SSL with Let's Encrypt

```bash
sudo certbot --nginx -d devflow.yourdomain.com
```

## Cron

Add to crontab (`crontab -e`):
```bash
* * * * * cd /var/www/devflow && php artisan schedule:run >> /dev/null 2>&1
```

## Health Check

Monitor `/up` endpoint:
```bash
curl -I https://devflow.yourdomain.com/up
```

## Monitoring

- **Laravel Pulse**: Access at `/pulse` (restrict to admins)
- **Logs**: `storage/logs/laravel.log`
- **Queue monitoring**: `php artisan queue:monitor`

## Scaling Considerations

1. **Database**: Use read replicas for heavy read workloads
2. **Cache**: Redis cluster for distributed caching
3. **Queue**: Multiple workers with Supervisor
4. **Reverb**: Multiple nodes with Redis adapter for broadcasting

## Backup Strategy

```bash
# Database backup
mysqldump -u devflow -p devflow > backup.sql

# Files backup
tar -czf storage_backup.tar.gz storage/app/public
```

## Troubleshooting

### Queue not processing
```bash
php artisan queue:work --verbose
```

### Permission denied on storage
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Cache issues
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```
