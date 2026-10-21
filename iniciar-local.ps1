Write-Host "======================================================" -ForegroundColor Cyan
Write-Host "   INICIANDO PROYECTO CITAS ESTETICAS (LARAVEL + VITE)" -ForegroundColor Cyan
Write-Host "======================================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "Entorno:" -ForegroundColor Yellow
Write-Host " - PHP: 8.2 (Laragon)"
Write-Host " - Base de datos: SQLite"
Write-Host " - URL: http://127.0.0.1:8000"
Write-Host ""
Write-Host "Credenciales de prueba:" -ForegroundColor Green
Write-Host " - Admin: admin@citasapp.com | Password: password"
Write-Host ""

$env:PATH = "C:\laragon\bin\php\php-8.2.30-nts-Win32-vs16-x64;C:\laragon\bin\composer;" + $env:PATH

# Esperar 3 segundos antes de abrir el navegador
Start-Job {
    Start-Sleep -Seconds 3
    Start-Process "http://127.0.0.1:8000"
} | Out-Null

npx concurrently -k -n "LARAVEL,VITE" -c "blue,magenta" "php artisan serve --port=8000" "npm run dev"
