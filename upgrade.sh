#!/bin/bash

# Laravel 13.x Upgrade Script
# This script will help complete the upgrade process

echo "======================================"
echo "Laravel 13.x Upgrade Script"
echo "======================================"
echo ""

# Step 1: Backup
echo "Step 1: Creating backup..."
echo "Please ensure you have backed up your database and files before proceeding."
read -p "Have you backed up your project? (y/n) " -n 1 -r
echo
if [[ ! $REPLY =~ ^[Yy]$ ]]
then
    echo "Please backup your project first, then run this script again."
    exit 1
fi

# Step 2: Check PHP version
echo ""
echo "Step 2: Checking PHP version..."
php_version=$(php -v | head -n 1 | cut -d " " -f 2 | cut -d "." -f 1,2)
echo "Current PHP version: $php_version"

if (( $(echo "$php_version < 8.3" | bc -l) )); then
    echo "ERROR: PHP 8.3 or higher is required (Laravel 13). Please upgrade PHP first."
    exit 1
fi
echo "✓ PHP version is compatible"

# Step 3: Remove old vendor directory
echo ""
echo "Step 3: Cleaning old dependencies..."
rm -rf vendor/
rm -rf composer.lock
echo "✓ Cleaned old dependencies"

# Step 4: Update Composer dependencies
echo ""
echo "Step 4: Installing new dependencies..."
composer install

if [ $? -ne 0 ]; then
    echo "ERROR: Composer install failed. Please check the errors above."
    exit 1
fi
echo "✓ Dependencies installed successfully"

# Step 5: Clear all caches
echo ""
echo "Step 5: Clearing application caches..."
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan clear-compiled
echo "✓ Caches cleared"

# Step 6: Run migrations
echo ""
echo "Step 6: Running database migrations..."
read -p "Do you want to run migrations? (y/n) " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]
then
    php artisan migrate
    echo "✓ Migrations completed"
fi

# Step 7: Optimize application
echo ""
echo "Step 7: Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
echo "✓ Application optimized"

# Step 8: Set permissions
echo ""
echo "Step 8: Setting proper permissions..."
chmod -R 775 storage bootstrap/cache
echo "✓ Permissions set"

echo ""
echo "======================================"
echo "Upgrade completed successfully!"
echo "======================================"
echo ""
echo "Next steps:"
echo "1. Test all forms and functionality"
echo "2. Check error logs for any issues"
echo "3. Review UPDATE_GUIDE.md for additional information"
echo ""
echo "If you encounter any issues, please check:"
echo "- storage/logs/laravel.log"
echo "- PHP error logs"
echo ""
