@echo off
setlocal
cd /d "%~dp0"
echo Iniciando instalador automatico de drivers...
powershell.exe -NoProfile -ExecutionPolicy Bypass -File "%~dp0Install-Drivers.ps1"
endlocal

