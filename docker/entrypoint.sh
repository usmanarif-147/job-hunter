#!/bin/sh
set -e

cd /var/www/html

echo "Bootstrapping Laravel..."

# 1. Create .env from .env.example if missing
if [ ! -f .env ]; then
    echo "Creating .env from .env.example..."                                                                                                                                         
    cp .env.example .env
fi 

# -----------------------------------
# 2. Install dependencies
# -----------------------------------
if [ ! -d vendor ]; then
    echo "Installing composer dependencies..."
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# 3. Generate APP_KEY if empty                                                                                                                                                        
if ! grep -q "^APP_KEY=base64:" .env; then
    echo "Generating APP_KEY..."                                                                                                                                                      
    php artisan key:generate --force                  
fi


# 4. Fix permissions on storage and cache (best-effort)
echo "Fixing permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null || true


# 6. Wait for MySQL
echo "Waiting for MySQL..."

until php -r "
try {
    new PDO('mysql:host=mysql;dbname=laravel_db', 'user', 'user');
    echo 'DB connected\n';
} catch (Exception \$e) {
    exit(1);
}
" > /dev/null 2>&1; do                                                                                                                                                                
    sleep 2                                           
done
echo "MySQL is ready."


# 7. Run migrations (background)
echo "Running migrations..."                                                                                                                                                          
php artisan migrate --force
                           
echo "Clearing config cache..."
php artisan config:clear

# -----------------------------------
# 8. Start PHP-FPM
# -----------------------------------
echo "Starting PHP-FPM..."
exec "$@"