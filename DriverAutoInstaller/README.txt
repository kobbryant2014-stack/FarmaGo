FarmaGo - Paquete de drivers del equipo
=======================================

Contenido:
- Drivers\: drivers exportados desde este equipo.
- Install-Drivers-Auto.bat: instalador para doble clic.
- Install-Drivers.ps1: instalador PowerShell con autoelevacion de administrador.
- Logs\: aqui se guardan los registros de instalacion.

Uso:
1. Copie esta carpeta al equipo donde desea instalar los drivers.
2. Ejecute Install-Drivers-Auto.bat.
3. Acepte el permiso de administrador cuando Windows lo solicite.
4. Reinicie Windows al finalizar si algun dispositivo lo pide.

Notas:
- El paquete instala drivers INF con PnPUtil.
- No instala aplicaciones completas de fabricantes, solo paquetes de driver exportados.
- Algunos drivers pueden omitirse si no corresponden al hardware del equipo destino.

