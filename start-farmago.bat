@echo off
cd /d %~dp0
echo Starting FarmaGo on http://0.0.0.0:8091
php artisan serve --host=0.0.0.0 --port=8091
pause
