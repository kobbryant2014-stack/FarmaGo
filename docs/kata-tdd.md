# Kata TDD: Calculo de total de venta farmaceutica con IGV

## Problema planteado

FarmaGo necesita calcular el total final de una venta considerando cantidad, precio unitario, descuentos por item, afectacion tributaria e IGV. Esta kata toma como base el ejercicio academico de calculo de IGV y lo adapta al dominio farmaceutico, donde existen productos gravados, exonerados e inafectos.

## Reglas de negocio

- Una venta debe tener al menos un item.
- La cantidad debe ser mayor a cero.
- El precio unitario no puede ser negativo.
- El descuento no puede ser negativo ni superar el subtotal del item.
- Los productos gravados usan afectacion tributaria `10` y generan IGV.
- Los productos exonerados usan `20` y no generan IGV.
- Los productos inafectos usan `30` y no generan IGV.
- Todos los importes se redondean a dos decimales.

## Iteraciones TDD

| Iteracion | Prueba | Resultado esperado |
|---|---|---|
| 1 | Venta gravada simple | Subtotal, base gravada, IGV y total correctos. |
| 2 | Venta con descuento | El descuento reduce la base imponible y se acumula. |
| 3 | Venta mixta | Se separan importes gravados, exonerados e inafectos. |
| 4 | Venta vacia | Se lanza una excepcion de regla de negocio. |
| 5 | Cantidad invalida | Se rechaza cantidad cero o negativa. |
| 6 | Descuento invalido | Se rechaza descuento mayor al subtotal. |

## Pruebas implementadas

Archivo: `tests/Unit/VentaTotalCalculatorTest.php`.

- `test_calcula_total_de_venta_gravada_con_igv`
- `test_aplica_descuento_sin_superar_el_subtotal`
- `test_separa_items_exonerados_e_inafectos_del_igv`
- `test_rechaza_venta_sin_items`
- `test_rechaza_cantidad_cero_o_negativa`
- `test_rechaza_descuento_mayor_al_subtotal`

## Mejoras realizadas

La logica se ubico en `app/Services/VentaTotalCalculator.php` para mantener controladores y modelos sin responsabilidades de calculo. El servicio es determinista, no depende de base de datos y por eso puede probarse como unidad pura.

## Resultado final

La kata deja una regla reutilizable para futuras ventas, comprobantes electronicos o validaciones de caja. Tambien funciona como evidencia academica del ciclo Red-Green-Refactor.

## Relacion con el ejemplo de clase

El ejemplo de clase propone probar el calculo de IGV, valores limite y errores con pruebas unitarias. En FarmaGo se aplico la misma idea con PHPUnit:

- Calculo correcto del IGV.
- Validacion de total con IGV.
- Manejo de datos invalidos mediante excepciones.
- Ampliacion del caso base hacia descuentos y afectaciones tributarias.
