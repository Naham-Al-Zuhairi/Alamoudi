$ErrorActionPreference = 'Stop'
$projectRoot = (Resolve-Path (Join-Path $PSScriptRoot '..')).Path
$uploadTemp = Join-Path $projectRoot 'storage/app/upload-tmp'
$phpConfig = Join-Path $projectRoot 'storage/app/php-local-ini'
New-Item -ItemType Directory -Path $uploadTemp -Force | Out-Null
New-Item -ItemType Directory -Path $phpConfig -Force | Out-Null

$settings = @"
upload_tmp_dir = "$uploadTemp"
upload_max_filesize = 5M
post_max_size = 72M
"@
[System.IO.File]::WriteAllText((Join-Path $phpConfig 'uploads.ini'), $settings, [System.Text.UTF8Encoding]::new($false))

$env:TEMP = $uploadTemp
$env:TMP = $uploadTemp
$env:PHP_INI_SCAN_DIR = $phpConfig

Push-Location $projectRoot
try {
    # Laravel passes PHP_INI_SCAN_DIR to its child only with --no-reload.
    php artisan serve --host=127.0.0.1 --port=8000 --no-reload
} finally {
    Pop-Location
}
