# Uso de IA generativa

## Herramienta usada

Se utilizo IA generativa mediante Codex en Visual Studio Code como apoyo tecnico para analizar, refactorizar, documentar y validar el sistema FarmaGo.

## Alcance del apoyo

La IA no reemplazo la revision humana. El equipo reviso, adapto y probo los cambios antes de considerarlos parte del sistema.

| Actividad | Apoyo de IA | Resultado | Revision humana |
| --- | --- | --- | --- |
| Analisis del sistema existente | Identifico estructura Laravel, servicios, modelos y riesgos. | Plan de adecuacion por fases. | Validacion contra requerimientos de la rubrica. |
| Correccion de totales | Propuso centralizar calculos de venta. | `VentaCalculatorService` y ajustes en `VentaService`. | Pruebas y revision de reglas FEFO. |
| Stock bajo y Kardex | Detecto riesgo con lotes vencidos y saldos por producto. | Scopes y metodos separados por producto/lote. | Revision de reglas de inventario. |
| Interfaz administrativa | Genero base de layout y vistas CRUD. | Layout AdminLTE y rutas conectadas. | Ajuste visual y validacion de rutas. |
| Manejo de excepciones | Sugirio mensajes claros y transacciones. | Controladores con `try/catch`, `report()` y `withErrors()`. | Revision de experiencia de usuario. |
| Documentacion academica | Estructuro Scrum, pruebas, informe y guion. | Archivos en `docs/`. | Adaptacion final por el equipo. |

## Declaracion academica

La IA generativa fue utilizada como apoyo para acelerar tareas tecnicas, detectar riesgos y estructurar documentacion. El equipo mantiene la responsabilidad sobre el diseno final, pruebas y decisiones del proyecto.
