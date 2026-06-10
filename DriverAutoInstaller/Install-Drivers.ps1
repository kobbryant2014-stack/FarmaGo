param(
    [switch]$NoPause
)

$ErrorActionPreference = "Stop"

function Test-IsAdministrator {
    $identity = [Security.Principal.WindowsIdentity]::GetCurrent()
    $principal = New-Object Security.Principal.WindowsPrincipal($identity)
    return $principal.IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)
}

if (-not (Test-IsAdministrator)) {
    $arguments = @(
        "-NoProfile",
        "-ExecutionPolicy", "Bypass",
        "-File", "`"$PSCommandPath`""
    )

    if ($NoPause) {
        $arguments += "-NoPause"
    }

    Start-Process -FilePath "powershell.exe" -ArgumentList $arguments -Verb RunAs
    exit
}

$root = Split-Path -Parent $PSCommandPath
$driversPath = Join-Path $root "Drivers"
$logsPath = Join-Path $root "Logs"
$timestamp = Get-Date -Format "yyyyMMdd-HHmmss"
$logFile = Join-Path $logsPath "install-drivers-$timestamp.log"
$pnputil = Join-Path $env:windir "System32\pnputil.exe"

New-Item -ItemType Directory -Force -Path $logsPath | Out-Null

if (-not (Test-Path -LiteralPath $driversPath)) {
    throw "No se encontro la carpeta Drivers en: $driversPath"
}

Write-Host "========================================"
Write-Host " Instalador automatico de drivers"
Write-Host "========================================"
Write-Host "Carpeta de drivers: $driversPath"
Write-Host "Log: $logFile"
Write-Host ""

Start-Transcript -Path $logFile -Force | Out-Null

try {
    Write-Host "Instalando drivers. Esto puede tardar varios minutos..."
    Write-Host ""

    & $pnputil /add-driver "$driversPath\*.inf" /subdirs /install
    $exitCode = $LASTEXITCODE

    Write-Host ""
    if ($exitCode -eq 0) {
        Write-Host "Instalacion completada correctamente."
    } else {
        Write-Host "PnPUtil termino con codigo: $exitCode"
        Write-Host "Revise el log para ver si algun driver no aplicaba a este equipo."
    }

    Write-Host ""
    Write-Host "Reinicie Windows si algun dispositivo lo solicita."
} finally {
    Stop-Transcript | Out-Null
}

if (-not $NoPause) {
    Write-Host ""
    Read-Host "Presione Enter para cerrar"
}

