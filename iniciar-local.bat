@echo off
title Citas Esteticas - Servidor Local
cls
echo ======================================================
echo    INICIANDO PROYECTO CITAS ESTETICAS (LARAVEL + VITE)
echo ======================================================
echo.
echo Entorno:
echo - PHP: 8.2 (Laragon)
echo - Base de datos: SQLite
echo - URL de la aplicacion: http://127.0.0.1:8000
echo.
echo Credenciales de prueba:
echo - Administrador: admin@citasapp.com ^| Clave: password
echo.
echo Presiona Ctrl+C en cualquier momento para detener los servidores.
echo.

set PATH=C:\laragon\bin\php\php-8.2.30-nts-Win32-vs16-x64;C:\laragon\bin\composer;%PATH%

rem Esperar 3 segundos antes de abrir el navegador
start /b cmd /c "timeout /t 3 > nul && start http://127.0.0.1:8000"

npx concurrently -k -n "LARAVEL,VITE" -c "blue,magenta" "php artisan serve --port=8000" "npm run dev"
