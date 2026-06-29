# Evidencia TDD: Red-Green-Refactor

| Iteracion | Requisito o historia de usuario | Prueba creada | Estado Red | Implementacion Green | Mejora Refactor | Archivos modificados |
|---|---|---|---|---|---|---|
| 1 | Como cajero, quiero calcular el total de una venta gravada para cobrar correctamente. | `test_calcula_total_de_venta_gravada_con_igv` | Fallo inicial: no existia `VentaTotalCalculator`. | Se creo el servicio con suma de subtotales e IGV. | Se devolvio un arreglo con nombres alineados a la tabla `ventas`. | `app/Services/VentaTotalCalculator.php`, `tests/Unit/VentaTotalCalculatorTest.php` |
| 2 | Como cajero, quiero aplicar descuentos validos por item. | `test_aplica_descuento_sin_superar_el_subtotal` | Fallo inicial: el descuento no se descontaba ni acumulaba. | Se resto el descuento de la base imponible. | Se centralizo la validacion del descuento por linea. | `app/Services/VentaTotalCalculator.php`, `tests/Unit/VentaTotalCalculatorTest.php` |
| 3 | Como responsable tributario, quiero separar items gravados, exonerados e inafectos. | `test_separa_items_exonerados_e_inafectos_del_igv` | Fallo inicial: todos los items eran tratados como gravados. | Se interpretaron codigos SUNAT `10`, `20` y `30`. | Se redondearon importes a dos decimales. | `app/Services/VentaTotalCalculator.php`, `tests/Unit/VentaTotalCalculatorTest.php` |
| 4 | Como sistema, debo rechazar ventas vacias o datos invalidos. | `test_rechaza_venta_sin_items`, `test_rechaza_cantidad_cero_o_negativa`, `test_rechaza_descuento_mayor_al_subtotal` | Fallo inicial: no existian excepciones de reglas de negocio. | Se agregaron excepciones `InvalidArgumentException`. | Se mantuvieron mensajes claros y reglas simples. | `app/Services/VentaTotalCalculator.php`, `tests/Unit/VentaTotalCalculatorTest.php` |
| 5 | Como desarrollador, quiero validar persistencia ORM con productos, ventas, lotes y relaciones. | `FarmaGoOrmTest` | Fallo inicial: no habia pruebas dedicadas para CRUD, relaciones y scopes ORM. | Se crearon escenarios con SQLite en memoria y `RefreshDatabase`. | Se uso `with()` para evidenciar eager loading y evitar N+1. | `tests/Feature/Orm/FarmaGoOrmTest.php` |

## Ciclo aplicado

1. Red: se escribieron primero pruebas que expresan reglas de venta, validaciones y comportamiento ORM esperado.
2. Green: se implemento el codigo minimo en `VentaTotalCalculator` y se reutilizaron modelos Eloquent existentes.
3. Refactor: se organizaron nombres, validaciones, redondeos y consultas con relaciones cargadas de forma explicita.

