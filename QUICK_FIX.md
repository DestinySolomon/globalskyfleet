# QUICK FIX - Missing Columns Error

## Problem
Error: "Column not found: 1054 Unknown column 'last_login_at'"

## Your Table Status
✅ Already has: `password_changed_at`, `is_active`, `deactivated_at`, `deactivation_reason`
❌ Missing: `last_login_at` and `last_login_ip`

## FASTEST FIX - Run This SQL in phpMyAdmin (2 minutes)

### Step 1: Login to phpMyAdmin
- Login to your cPanel
- Open phpMyAdmin
- Select database: `globobqy_courier`

### Step 2: Run This SQL
Go to the SQL tab and paste this:

```sql
ALTER TABLE `users` 
ADD COLUMN `last_login_at` TIMESTAMP NULL DEFAULT NULL AFTER `email_verified_at`,
ADD COLUMN `last_login_ip` VARCHAR(255) NULL DEFAULT NULL AFTER `last_login_at`;
```

Click **Go** to execute.

### Step 3: Verify
Check if columns were added:

```sql
SELECT COLUMN_NAME, DATA_TYPE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'users' 
  AND TABLE_SCHEMA = 'globobqy_courier'
  AND COLUMN_NAME IN ('last_login_at', 'last_login_ip');
```

You should see 2 rows returned.

### Step 4: Clear Cache
If you have SSH access:
```bash
cd /path/to/your/application
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

Or create a temporary route in `routes/web.php`:
```php
Route::get('/clear-cache', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    return 'Cache cleared!';
});
```
Then visit: `https://globalskyfleet.com/clear-cache`

**Remember to remove this route after clearing cache!**

### Step 5: Test Login
Try logging in again. It should work now!

---

## Alternative: Using Laravel Migration (If You Have SSH)

If you prefer using Laravel migration:

```bash
cd /path/to/your/application
php artisan migrate --path=database/migrations/2026_01_31_000001_add_login_tracking_to_users_table.php
php artisan cache:clear
php artisan config:clear
```

---

## What These Columns Do

- `last_login_at`: Records when the user last logged in
- `last_login_ip`: Records the IP address from the last login

Both are used for security tracking and are populated automatically when users log in.

---

## Troubleshooting

### Still getting the error?
1. Verify columns exist in phpMyAdmin (check users table structure)
2. Clear all caches
3. Check your .env file has correct database credentials
4. Restart PHP-FPM if possible (or ask hosting support)

### Can't access phpMyAdmin?
Contact your hosting support and ask them to run the SQL commands above.

---

## Files to Upload
Make sure you've also uploaded the updated PHP files:
- `app/Models/User.php`
- `app/Http/Controllers/Auth/LoginController.php`
- `app/Http/Controllers/UserProfileController.php`
- `app/Http/Controllers/AdminController.php`
- `app/Http/Middleware/CheckUserActive.php`
- `bootstrap/app.php`

All the migration files in `database/migrations/`
