# Server Configuration for 500MB Video Uploads

## Current Settings

✅ **Laravel Validation**: 500MB (configured in LessonController.php)

## Server Configuration Needed

### Step 1: Configure PHP Settings

```bash
# Find your php.ini file
php --ini

# Edit php.ini (adjust version number if needed)
sudo nano /etc/php/8.2/fpm/php.ini
```

Add/update these values:
```ini
upload_max_filesize = 500M
post_max_size = 510M
max_execution_time = 300
memory_limit = 512M
```

**Restart PHP-FPM:**
```bash
sudo systemctl restart php8.2-fpm
```

### Step 2: Configure Nginx

```bash
# Edit your site configuration
sudo nano /etc/nginx/sites-available/complero
```

Add this inside the `server` block:
```nginx
client_max_body_size 500M;
```

**Restart Nginx:**
```bash
sudo systemctl restart nginx
```

### Step 3: Verify Settings

```bash
# Check PHP settings
php -r "echo ini_get('upload_max_filesize');" # Should show: 500M
php -r "echo ini_get('post_max_size');" # Should show: 510M

# Check Nginx config is valid
sudo nginx -t
```

## Quick One-Liner Check

```bash
echo "Upload Max: $(php -r 'echo ini_get(\"upload_max_filesize\");')" && \
echo "Post Max: $(php -r 'echo ini_get(\"post_max_size\");')" && \
echo "Memory Limit: $(php -r 'echo ini_get(\"memory_limit\");')" && \
echo "Max Execution: $(php -r 'echo ini_get(\"max_execution_time\");')s"
```

## Troubleshooting

If uploads still fail:
1. Check Laravel logs: `tail -f storage/logs/laravel.log`
2. Check Nginx error log: `sudo tail -f /var/log/nginx/error.log`
3. Check PHP-FPM log: `sudo tail -f /var/log/php8.2-fpm.log`
