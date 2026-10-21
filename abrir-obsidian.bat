@echo off
setlocal
echo ================================================================
echo   Iniciando Boveda de Mapeo del Proyecto en Obsidian
echo ================================================================

set "VAULT_DIR=%~dp0docs\obsidian-vault"
set "OBSIDIAN_EXE=%LOCALAPPDATA%\Programs\Obsidian\Obsidian.exe"

if exist "%OBSIDIAN_EXE%" (
    echo [OK] Obsidian detectado en: %OBSIDIAN_EXE%
    start "" "%OBSIDIAN_EXE%" "%VAULT_DIR%"
    goto fin
)

where obsidian >nul 2>nul
if %ERRORLEVEL% equ 0 (
    echo [OK] Obsidian detectado en PATH del sistema.
    start "" obsidian "%VAULT_DIR%"
    goto fin
)

echo [!] Abriendo mediante protocolo obsidian://...
start "" "obsidian://open?path=%VAULT_DIR%"

:fin
echo.
echo Boveda cargada correctamente en Obsidian.
echo Ubicacion: %VAULT_DIR%
endlocal
