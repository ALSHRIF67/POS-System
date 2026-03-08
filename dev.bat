
@echo off
title Laravel POS Dev

echo Starting Laravel POS...

cd /d C:\xampp\htdocs\POS\pos

echo Starting MySQL...
start "" C:\xampp\xampp_start.exe

timeout /t 5

echo Starting Laravel server...
start cmd /k php artisan serve

echo Starting Vite...
start cmd /k npm run dev

timeout /t 3

echo Opening browser...
start http://127.0.0.1:8000

pause