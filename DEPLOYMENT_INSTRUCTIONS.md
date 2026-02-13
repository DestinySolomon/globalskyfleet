# Production Deployment Instructions

## Issue
You're getting an error: "Column not found: 1054 Unknown column 'last_login_at' in 'SET'"

## Solution
You need to run the new migrations on your production server to add the missing columns.

## Steps to Deploy to cPanel:

### 1. Upload Files
Upload these new migration files to your cPanel:
- `database/migrations/2026_01_31_000000_add_deactivation_to_users_table.php`
- `database/migrations/2026_01_31_000001_add_login_tracking_to_users_table.php`

Also upload ALL updated files including:
- `app/Models/User.php`
- `app/Http/Controllers/UserProfileController.php`
- `app/Http/Controllers/AdminController.php`
- `app/Http/Controllers/Auth/LoginController.php`
- `app/Http/Middleware/CheckUserActive.php`
- `bootstrap/app.php`

### 2. Run Migrations via SSH or Terminal
Connect to your cPanel via SSH or use the Terminal feature, then run:

```bash
cd /path/to/your/application
php artisan migrate
```

### 3. If SSH is not available
If you can't access SSH, you can create a temporary migration route:

**Option A: Use Laravel Tinker (if available in cPanel)**
- Go to your application directory
- Run: `php artisan tinker`
- Execute these commands:
```php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

Schema::table('users', function (Blueprint $table) {
    if (!Schema::hasColumn('users', 'last_login_at')) {
        $table->timestamp('last_login_at')->nullable()->after('email_verified_at');
    }
    if (!Schema::hasColumn('users', 'last_login_ip')) {
        $table->string('last_login_ip')->nullable()->after('last_login_at');
    }
    if (!Schema::hasColumn('users', 'password_changed_at')) {
        $table->timestamp('password_changed_at')->nullable()->after('password');
    }
    if (!Schema::hasColumn('users', 'is_active')) {
        $table->boolean('is_active')->default(true)->after('email_verified_at');
    }
    if (!Schema::hasColumn('users', 'deactivated_at')) {
        $table->timestamp('deactivated_at')->nullable();
    }
    if (!Schema::hasColumn('users', 'deactivation_reason')) {
        $table->text('deactivation_reason')->nullable();
    }
});
```

**Option B: Direct SQL (via phpMyAdmin)**
Run these SQL commands in phpMyAdmin:

```sql
ALTER TABLE `users` 
ADD COLUMN `last_login_at` TIMESTAMP NULL AFTER `email_verified_at`,
ADD COLUMN `last_login_ip` VARCHAR(255) NULL AFTER `last_login_at`,
ADD COLUMN `password_changed_at` TIMESTAMP NULL AFTER `password`,
ADD COLUMN `is_active` TINYINT(1) NOT NULL DEFAULT 1 AFTER `email_verified_at`,
ADD COLUMN `deactivated_at` TIMESTAMP NULL AFTER `is_active`,
ADD COLUMN `deactivation_reason` TEXT NULL AFTER `deactivated_at`;
```

### 4. Update Existing Users (via phpMyAdmin or Tinker)
Set all existing users to active:

```sql
UPDATE `users` SET `is_active` = 1 WHERE `is_active` IS NULL OR `is_active` = 0;
```

### 5. Clear Cache
After migration, clear all caches:

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

Or via code (create a temporary route if needed):
```php
Artisan::call('cache:clear');
Artisan::call('config:clear');
Artisan::call('route:clear');
Artisan::call('view:clear');
```

## What These Migrations Do:

### Migration 1: Deactivation Tracking
- `is_active` - Boolean flag for account status (default: true)
- `deactivated_at` - Timestamp when account was deactivated
- `deactivation_reason` - Why account was deactivated

### Migration 2: Login Tracking
- `last_login_at` - Timestamp of last successful login
- `last_login_ip` - IP address of last login
- `password_changed_at` - Timestamp of last password change

## Verification
After running migrations, verify in phpMyAdmin that all these columns exist in the `users` table.

## Troubleshooting

### If you still get errors:
1. Check that migrations ran successfully
2. Verify columns exist in database
3. Clear all caches
4. Check file permissions (storage and bootstrap/cache should be writable)

### Common Issues:
- **404 on pages**: Run `php artisan route:clear`
- **Config issues**: Run `php artisan config:clear`
- **View errors**: Run `php artisan view:clear`
- **Permission denied**: Make sure storage folder is writable (chmod 775 or 777)

## Support
If you encounter issues, check the Laravel logs at:
`storage/logs/laravel.log`
