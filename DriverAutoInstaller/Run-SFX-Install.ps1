$ErrorActionPreference = "Stop"

function Test-IsAdministrator {
    $identity = [Security.Principal.WindowsIdentity]::GetCurrent()
    $principal = New-Object Security.Principal.WindowsPrincipal($identity)
    return $principal.IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)
}

if (-not (Test-IsAdministrator)) {
    Start-Process -FilePath "powershell.exe" -ArgumentList @(
        "-NoProfile",
        "-ExecutionPolicy", "Bypass",
        "-File", "`"$PSCommandPath`""
    ) -Verb RunAs
    exit
}

$packageRoot = Split-Path -Parent $PSCommandPath
$zipPath = Join-Path $packageRoot "DriverPackage.zip"
$destination = Join-Path $env:TEMP ("driver-lap-" + (Get-Date -Format "yyyyMMdd-HHmmss"))

if (-not (Test-Path -LiteralPath $zipPath)) {
    throw "No se encontro DriverPackage.zip en: $packageRoot"
}

New-Item -ItemType Directory -Force -Path $destination | Out-Null

Write-Host "Extrayendo paquete de drivers..."
Expand-Archive -LiteralPath $zipPath -DestinationPath $destination -Force

$installer = Join-Path $destination "Install-Drivers.ps1"
if (-not (Test-Path -LiteralPath $installer)) {
    throw "No se encontro Install-Drivers.ps1 dentro del paquete extraido."
}

& powershell.exe -NoProfile -ExecutionPolicy Bypass -File $installer

