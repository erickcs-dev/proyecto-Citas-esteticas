# Sincronizador de Mapeo y Memoria del Proyecto (Obsidian)
[CmdletBinding()]
param()

$ProjectRoot = Split-Path -Parent $PSScriptRoot
$VaultDir = Join-Path $ProjectRoot "docs\obsidian-vault"
$EntitiesDir = Join-Path $VaultDir "entidades"

Write-Host "================================================================" -ForegroundColor Cyan
Write-Host "   Sincronizador de Mapeo y Memoria del Proyecto (Obsidian)    " -ForegroundColor Cyan
Write-Host "================================================================" -ForegroundColor Cyan

if (-not (Test-Path $EntitiesDir)) {
    New-Item -ItemType Directory -Path $EntitiesDir -Force | Out-Null
}

# 1. Escaneo de Modelos Eloquent
$modelsPath = Join-Path $ProjectRoot "app\Models"
$modelFiles = Get-ChildItem -Path $modelsPath -Filter "*.php" -File -ErrorAction SilentlyContinue
$totalModels = ($modelFiles | Measure-Object).Count
Write-Host "`n[1] Modelos Eloquent detectados ($totalModels):" -ForegroundColor Green

$createdCount = 0
foreach ($model in $modelFiles) {
    $modelName = [System.IO.Path]::GetFileNameWithoutExtension($model.Name)
    $notePath = Join-Path $EntitiesDir "$modelName.md"
    
    if (Test-Path $notePath) {
        Write-Host "  [OK] $modelName ya documentado en Obsidian" -ForegroundColor Gray
    } else {
        Write-Host "  [+] Creando nueva ficha de mapeo para $modelName..." -ForegroundColor Yellow
        $dateStr = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
        $lines = @(
            '---',
            'tags:',
            '  - modelo',
            '  - eloquent',
            '  - entidad',
            '  - auto-sincronizado',
            'padre: "[[03 - Modelo de Datos y Esquema ER]]"',
            "creado: $dateStr",
            '---',
            '',
            "# Entidad Eloquent: $modelName",
            '',
            "- **Archivo PHP**: ``app/Models/$($model.Name)``",
            '- **Tabla en BD**: Calculada por convención Eloquent',
            '',
            '---',
            '',
            '## Atributos y Fillable',
            'Documentar atributos del modelo según migración correspondiente.',
            '',
            '## Relaciones Eloquent',
            'Documentar relaciones (hasMany, belongsTo, etc.)'
        )
        $content = $lines -join "`r`n"
        Set-Content -Path $notePath -Value $content -Encoding UTF8
        $createdCount++
    }
}

# 2. Escaneo de Controladores y Servicios
$controllers = Get-ChildItem -Path (Join-Path $ProjectRoot "app\Http\Controllers") -Filter "*Controller.php" -Recurse -File
$services = Get-ChildItem -Path (Join-Path $ProjectRoot "app\Services") -Filter "*Service.php" -File
$migrations = Get-ChildItem -Path (Join-Path $ProjectRoot "database\migrations") -Filter "*.php" -File

Write-Host "`n[2] Resumen de Componentes del Sistema:" -ForegroundColor Green
Write-Host "  - Controladores HTTP: $($controllers.Count)" -ForegroundColor White
Write-Host "  - Servicios de Dominio: $($services.Count)" -ForegroundColor White
Write-Host "  - Migraciones de BD: $($migrations.Count)" -ForegroundColor White

# 3. Verificación de Canvas y MOC
$canvasPath = Join-Path $VaultDir "Arquitectura-General.canvas"
$mocPath = Join-Path $VaultDir "00 - MOC - Indice Maestro.md"

Write-Host "`n[3] Integridad de la Boveda de Obsidian:" -ForegroundColor Green
if (Test-Path $canvasPath) {
    Write-Host "  [OK] Arquitectura-General.canvas presente" -ForegroundColor Gray
} else {
    Write-Host "  [!] Falta Arquitectura-General.canvas" -ForegroundColor Red
}

if (Test-Path $mocPath) {
    Write-Host "  [OK] 00 - MOC - Indice Maestro.md presente" -ForegroundColor Gray
} else {
    Write-Host "  [!] Falta 00 - MOC - Indice Maestro.md" -ForegroundColor Red
}

Write-Host "`n[OK] Sincronizacion finalizada con exito. Nuevas fichas creadas: $createdCount" -ForegroundColor Cyan
Write-Host "Para abrir la boveda, ejecuta .\abrir-obsidian.bat o abre docs\obsidian-vault en Obsidian." -ForegroundColor Yellow
