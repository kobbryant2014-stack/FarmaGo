# FarmaGo - Referencias regulatorias iniciales

Estas referencias guian el diseno funcional. Antes de salir a produccion se debe validar la version vigente de normas, catalogos SUNAT, manuales UBL y reglas sanitarias aplicables al establecimiento.

## SUNAT / CPE

Fuentes oficiales:

- SUNAT CPE - Tipos de comprobantes: https://cpe.sunat.gob.pe/tipos_de_comprobantes/factura
- SUNAT Orientacion - Comprobantes electronicos: https://orientacion.sunat.gob.pe/02-comprobantes-de-pago-emitidos-de-manera-electronica
- SUNAT CPE - Boleta electronica: https://cpe.sunat.gob.pe/tipos_de_comprobantes/boleta
- SUNAT CPE - Guias y manuales: https://cpe.sunat.gob.pe/guias-y-manuales
- SUNAT CPE - Certificado digital: https://cpe.sunat.gob.pe/certificado-digital
- SUNAT CPE - Proveedor de Servicios Electronicos PSE: https://cpe.sunat.gob.pe/aliados/pse
- SUNAT CPE - Operador de Servicios Electronicos OSE: https://cpe.sunat.gob.pe/aliados/ose
- SUNAT CPE - Sistema de Emision OSE: https://cpe.sunat.gob.pe/informacion_general/operador_servicios_electronicos

Implicancias para el sistema:

- Soportar factura, boleta, nota de credito y nota de debito electronica.
- Usar serie alfanumerica y numeracion correlativa por tipo/serie.
- Guardar XML, PDF/representacion impresa, QR y CDR.
- Modelar estados CPE y errores de validacion.
- Implementar adaptadores para SUNAT directo, OSE o PSE.
- Mantener responsabilidad del contenido del comprobante en el emisor, aunque se use PSE.
- Usar certificado digital o firma delegada segun modalidad.
- Permitir consulta/reenvio y trazabilidad de comprobantes enviados.

## DIGEMID / Medicamentos con receta y controlados

Fuentes oficiales:

- DIGEMID - Psicotropicos y estupefacientes: https://www.digemid.minsa.gob.pe/webDigemid/psicotropicos-y-estupefacientes/
- MINSA/DIGEMID - Farmacias y boticas deben exigir receta: https://www.gob.pe/institucion/minsa/noticias/962205-farmacias-y-boticas-deben-exigir-receta-antes-de-vender-medicamentos-que-requieren-prescripcion-medica
- DIGEMID - Estandares de productos farmaceuticos: https://www.digemid.minsa.gob.pe/webDigemid/estandares-de-productos-farmaceuticos/

Implicancias para el sistema:

- Producto debe tener condicion de venta: libre, receta, receta retenida, receta especial/controlado.
- POS debe bloquear productos que requieren receta si no existe receta valida.
- Controlados deben generar registro de dispensacion y auditoria reforzada.
- Receta debe conservar datos de medico, paciente, producto, fecha y estado.
- Datos sanitarios deben tener acceso restringido.

## Proteccion de datos personales

Fuentes oficiales:

- Ley 29733 - Ley de Proteccion de Datos Personales: https://www.gob.pe/institucion/congreso-de-la-republica/normas-legales/243470-29733
- ANPD - Nuevo reglamento LPDP: https://www.gob.pe/institucion/anpd/campa%C3%B1as/128319-nuevo-reglamento-de-proteccion-de-datos-personales
- ANPD - Inscripcion de banco de datos personales: https://www.gob.pe/8060-inscribir-banco-de-datos-en-el-registro-nacional-de-proteccion-de-datos-personales

Implicancias para el sistema:

- Identificar finalidades: facturacion, receta, auditoria, fidelizacion si se activa.
- Aplicar minimo privilegio y enmascaramiento.
- Cifrar credenciales, certificados y datos sensibles cuando corresponda.
- Registrar consentimientos si se almacenan datos no estrictamente tributarios/sanitarios.
- Preparar inventario de bancos de datos personales.
- Mantener trazabilidad de acceso a recetas y datos de salud.

## Notas de implementacion

- Esta documentacion no sustituye asesoria tributaria, legal ni sanitaria.
- Para CPE se debe hacer homologacion/pruebas con el proveedor elegido.
- Catalogos SUNAT deben cargarse como datos versionados, no hardcodearse.
- Las reglas de recetas/controlados deben validarse con el director tecnico de la botica/farmacia.
