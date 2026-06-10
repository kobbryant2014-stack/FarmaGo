# Start FarmaGo Laravel server on port 8091
Set-Location "C:\xampp\htdocs\FarmaGo"
Write-Host "Starting FarmaGo on http://0.0.0.0:8091"
php artisan serve --host=0.0.0.0 --port=8091
