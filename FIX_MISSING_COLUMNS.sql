-- Fix for missing last_login_at and last_login_ip columns
-- Run this in phpMyAdmin on your production database (globobqy_courier)

-- Add last_login_at column after email_verified_at
ALTER TABLE `users` 
ADD COLUMN `last_login_at` TIMESTAMP NULL DEFAULT NULL AFTER `email_verified_at`;

-- Add last_login_ip column after last_login_at
ALTER TABLE `users` 
ADD COLUMN `last_login_ip` VARCHAR(255) NULL DEFAULT NULL AFTER `last_login_at`;

-- Verify columns were added
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'users' 
  AND TABLE_SCHEMA = 'globobqy_courier'
  AND COLUMN_NAME IN ('last_login_at', 'last_login_ip')
ORDER BY ORDINAL_POSITION;
