# FarmaGo - Diccionario de datos inicial

Este diccionario define la estructura objetivo inicial. Los nombres finales pueden ajustarse al crear migraciones, pero no deben perder las reglas de integridad, auditoria y trazabilidad.

## Convenciones

- `id`: bigint autoincremental.
- `status`: estado funcional del registro.
- `created_by`, `updated_by`: usuario responsable cuando corresponda.
- `deleted_at`: solo para maestros; no para ventas, CPE, movimientos de stock, caja ni auditoria.
- Campos monetarios: decimal(14,2) o decimal(16,6) para costos unitarios si se requiere precision.
- Cantidades: decimal(14,3) para soportar fraccionamiento; integer solo cuando el dominio no permita fraccion.
- Documentos tributarios y sanitarios se conservan historicamente.

## Core y seguridad

### companies

Empresa emisora.

- ruc: string(11), unico.
- legal_name: string(255).
- commercial_name: string(255), nullable.
- fiscal_address: string(255).
- ubigeo: string(6), nullable.
- phone, email, logo_path.
- currency_code: string(3), default PEN.
- igv_rate: decimal(5,2), configurable.
- status.

Indices:

- unique(ruc)
- index(status)

### branches

Sedes o locales.

- company_id: FK companies.
- code: string(20).
- name: string(120).
- address: string(255).
- ubigeo: string(6).
- status.

Indices:

- unique(company_id, code)
- index(company_id, status)

### warehouses

Almacenes por sede.

- branch_id: FK branches.
- code, name.
- status.

### users

Usuarios del sistema.

- name.
- email, unico.
- password.
- active: boolean.
- last_login_at.
- last_login_ip.
- failed_login_attempts.
- locked_until.

## Productos e inventario

### product_categories

- parent_id: FK nullable para subcategoria.
- name.
- description.
- status.

### laboratories

- name.
- ruc nullable.
- country nullable.
- status.

### active_ingredients

- name.
- description nullable.
- status.

### product_presentations

- name: tableta, jarabe, crema, ampolla, blister, caja, etc.
- unit_code: codigo de unidad tributaria/operativa.
- status.

### products

- category_id: FK product_categories.
- laboratory_id: FK laboratories nullable.
- active_ingredient_id: FK active_ingredients nullable.
- presentation_id: FK product_presentations nullable.
- internal_code: string(50), unico.
- barcode: string(80), indexado.
- commercial_name.
- dci.
- active_principle.
- concentration.
- pharmaceutical_form.
- presentation_text.
- manufacturer.
- sanitary_registration.
- sale_condition: libre, receta, receta_retenida, receta_especial.
- unit_of_measure.
- purchase_price.
- sale_price.
- unit_price.
- box_price.
- blister_price.
- stock_min.
- stock_max.
- tax_affectation_code.
- igv_rate.
- requires_prescription: boolean.
- requires_retained_prescription: boolean.
- is_controlled: boolean.
- cold_chain_required: boolean.
- warehouse_location.
- image_path.
- observations.
- status: active, inactive, immobilized, withdrawn.

Indices:

- unique(internal_code)
- index(barcode)
- index(commercial_name)
- index(dci)
- index(active_principle)
- index(sanitary_registration)
- index(status)

### product_barcodes

Codigos alternos.

- product_id: FK products.
- barcode.
- package_type: unidad, blister, caja.

### product_prices

Historial de precios.

- product_id.
- price_type.
- price.
- valid_from.
- valid_to nullable.
- changed_by.

### product_lots

Lotes de producto.

- product_id: FK products.
- warehouse_id: FK warehouses.
- supplier_id: FK suppliers nullable.
- purchase_id: FK purchases nullable.
- lot_number.
- manufacture_date nullable.
- expiration_date.
- initial_quantity.
- unit_cost.
- status: active, expired, immobilized, withdrawn, depleted.
- blocked_reason nullable.

Indices:

- unique(product_id, lot_number)
- index(product_id, expiration_date, status)
- index(warehouse_id, status)

### stock_movements

Ledger de inventario.

- warehouse_id.
- product_id.
- product_lot_id.
- user_id.
- type: purchase_in, sale_out, adjustment_in, adjustment_out, transfer_in, transfer_out, return_in, expired_out, reversal.
- quantity.
- unit_cost nullable.
- origin_type.
- origin_id.
- reason.
- status: valid, reversed.
- reversed_movement_id nullable.

Indices:

- index(product_id, created_at)
- index(product_lot_id, created_at)
- index(origin_type, origin_id)

## Compras y proveedores

### suppliers

- document_type.
- ruc.
- business_name.
- address.
- phone.
- email.
- contact_name.
- status.

### purchases

- company_id.
- branch_id.
- supplier_id.
- user_id.
- supplier_document_type.
- supplier_document_series.
- supplier_document_number.
- issue_date.
- due_date nullable.
- subtotal.
- discount_total.
- igv_total.
- total.
- payment_status: pending, partial, paid.
- status: received, cancelled, returned.

### purchase_details

- purchase_id.
- product_id.
- product_lot_id.
- quantity.
- unit_cost.
- discount.
- igv.
- total.

### supplier_payments

- purchase_id.
- payment_method_id.
- paid_at.
- amount.
- reference.
- status.

## Clientes, pacientes y recetas

### customers

- identity_document_type.
- document_number.
- first_name.
- last_name.
- business_name.
- address.
- phone.
- email.
- birth_date nullable.
- frequent_customer boolean.
- loyalty_points nullable.
- data_processing_consent boolean.
- status.

Indices:

- index(document_number)
- index(business_name)

### patients

Separado si el paciente no siempre es el comprador.

- customer_id nullable.
- identity_document_type.
- document_number.
- first_name.
- last_name.
- birth_date nullable.
- sensitive_notes encrypted nullable.
- status.

### doctors

- full_name.
- cmp_number.
- specialty nullable.
- phone nullable.
- status.

### prescriptions

- patient_id.
- customer_id nullable.
- doctor_id.
- prescription_number.
- issue_date.
- expiration_date.
- prescription_type: simple, retained, special.
- attachment_path nullable.
- diagnosis encrypted nullable.
- observations.
- validated_by nullable.
- validated_at nullable.
- status: registered, validated, observed, used, partially_used, expired, cancelled.

### prescription_details

- prescription_id.
- product_id nullable.
- prescribed_name.
- dose.
- quantity_prescribed.
- quantity_dispensed.
- frequency.
- observations.

## Ventas, POS y devoluciones

### sales

- company_id.
- branch_id.
- cash_session_id.
- customer_id nullable.
- user_id.
- sale_datetime.
- subtotal.
- discount_total.
- taxable_total.
- exonerated_total.
- unaffected_total.
- igv_total.
- total.
- status: draft, completed, cancelled, returned, partially_returned.

### sale_details

- sale_id.
- product_id.
- product_lot_id.
- prescription_id nullable.
- quantity.
- sale_unit: unit, box, blister.
- unit_price.
- discount.
- tax_affectation_code.
- igv.
- total.

### sale_payments

- sale_id.
- payment_method_id.
- amount.
- received_amount nullable.
- change_amount nullable.
- reference nullable.
- status.

### returns

- sale_id.
- electronic_document_id nullable.
- authorized_by.
- reason.
- return_type: total, partial.
- impact_stock boolean.
- impact_cash boolean.
- status.

### return_details

- return_id.
- sale_detail_id.
- product_id.
- product_lot_id.
- quantity.
- amount.
- restock_status: restocked, blocked, discarded.

## Facturacion electronica

### sunat_configurations

- company_id.
- provider_type: sunat, ose, pse, api.
- sol_user_encrypted nullable.
- sol_password_encrypted nullable.
- certificate_path nullable.
- certificate_password_encrypted nullable.
- endpoint_url nullable.
- environment: beta, production.
- status.

### document_series

- company_id.
- branch_id.
- document_type: 01, 03, 07, 08, guia si aplica.
- series.
- current_number.
- status.

Indices:

- unique(company_id, branch_id, document_type, series)

### electronic_documents

- company_id.
- branch_id.
- sale_id nullable.
- customer_id nullable.
- document_type.
- series.
- number.
- issue_date.
- currency_code.
- subtotal.
- igv_total.
- total.
- qr_payload.
- xml_hash.
- provider_type.
- status.
- sunat_status nullable.
- cdr_code nullable.
- cdr_description nullable.
- sent_at nullable.
- accepted_at nullable.

Indices:

- unique(company_id, document_type, series, number)
- index(status)
- index(issue_date)

### electronic_document_items

- electronic_document_id.
- product_id nullable.
- description.
- quantity.
- unit_price.
- tax_affectation_code.
- igv.
- total.

### electronic_document_files

- electronic_document_id.
- file_type: xml, signed_xml, pdf, ticket_pdf, cdr_zip.
- disk.
- path.
- sha256.
- status.

### electronic_document_events

- electronic_document_id.
- event_type.
- previous_status.
- new_status.
- request_payload nullable.
- response_payload nullable.
- error_message nullable.
- user_id nullable.

### credit_notes

- electronic_document_id.
- reference_document_id.
- reason_code.
- reason_description.
- total.
- status.

### debit_notes

- electronic_document_id.
- reference_document_id.
- reason_code.
- reason_description.
- total.
- status.

## Caja y pagos

### payment_methods

- code.
- name: efectivo, tarjeta, yape, plin, transferencia, credito.
- requires_reference boolean.
- status.

### cash_registers

- branch_id.
- code.
- name.
- status.

### cash_sessions

- cash_register_id.
- user_id.
- opened_at.
- closed_at nullable.
- opening_amount.
- expected_amount nullable.
- counted_amount nullable.
- difference nullable.
- status: open, closed.

### cash_movements

- cash_session_id.
- payment_method_id nullable.
- user_id.
- type: opening, sale, income, expense, refund, withdrawal, adjustment, closing.
- amount.
- origin_type nullable.
- origin_id nullable.
- reason.
- status.

## Medicamentos controlados

### controlled_medicines

- product_id.
- control_type.
- registry_code nullable.
- requires_special_prescription boolean.
- status.

### controlled_medicine_movements

- controlled_medicine_id.
- product_lot_id.
- prescription_id nullable.
- sale_id nullable.
- user_id.
- type: entry, sale, return, loss, adjustment, reversal.
- quantity.
- reason.
- authorized_by nullable.
- ip_address.
- status.

## Alertas, auditoria y mantenimiento

### alerts

- branch_id nullable.
- alert_type.
- severity: info, warning, critical.
- entity_type.
- entity_id.
- title.
- message.
- due_date nullable.
- read_at nullable.
- status.

### audit_logs

- user_id nullable.
- action.
- module.
- entity_type.
- entity_id.
- old_values JSON nullable.
- new_values JSON nullable.
- reason nullable.
- ip_address.
- user_agent.
- status.
- created_at.

Indices:

- index(user_id, created_at)
- index(module, created_at)
- index(entity_type, entity_id)

### backups

- requested_by nullable.
- backup_type: manual, scheduled.
- disk.
- path.
- size_bytes.
- sha256.
- started_at.
- finished_at nullable.
- status: running, completed, failed.
- error_message nullable.

## Relaciones criticas

- product_lots depende de products, warehouses y opcionalmente purchases/suppliers.
- stock_movements referencia product_lots y nunca se elimina.
- sales depende de cash_sessions.
- sale_details consume product_lots.
- electronic_documents referencia sales y guarda archivos.
- credit_notes/debit_notes referencian CPE original.
- prescriptions se asocian a patients/doctors y pueden ligarse a sale_details.
- controlled_medicine_movements siempre referencia lote y usuario.
- audit_logs cruza todos los modulos por entity_type/entity_id.
