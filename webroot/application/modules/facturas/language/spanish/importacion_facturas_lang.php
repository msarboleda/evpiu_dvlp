<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Archivo de lenguajes en español para el controlador de importación
* de facturas.
*
* @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
* @author Martin Arboleda Montoya <sistemas@estradavelasquez.com>
* @copyright 2018 CI Estrada Velasquez y Cia S.A.S
*/

// Import invoices from WinPOS to DMS
$lang['IIWD_heading'] = 'Importar facturas desde WinPOS a DMS';
$lang['IIWD_point_sale_label'] = 'Punto de venta';
$lang['IIWD_invoices_date_label'] = 'Facturas de la fecha';
$lang['IIWD_see_last_invoice_submit_button'] = 'Consultar facturas';
$lang['IIWD_last_point_sale_label'] = 'Punto de venta';
$lang['IIWD_last_invoice_number_label'] = 'Número';
$lang['IIWD_last_invoice_date_label'] = 'Fecha';

// Show invoices from sale point
$lang['SISP_heading'] = 'Mostrar Facturas de Punto de Venta';
$lang['void_invoice_successfully_reported'] = 'Esta factura se reportó como anulada correctamente.';
$lang['void_invoice_no_reported'] = 'No se reportó esta factura como anulada.';
$lang['normal_invoice_successfully_reported'] = 'Esta factura se reportó en DMS correctamente.';
$lang['normal_invoice_no_reported'] = 'No se reportó esta factura.';
$lang['accounting_imputation_successfully_reported'] = 'La imputación contable se reportó en DMS correctamente.';
$lang['accounting_imputation_no_reported'] = 'No se reportó la imputación contable.';
$lang['customer_not_created_on_dms'] = 'El cliente no está creado en DMS.';
$lang['customer_nit_does_not_exist_on_winpos'] = 'No existe NIT en WinPOS para este cliente:';

// Generate manual invoice structure
$lang['manual_invoice_error'] = 'Esta factura se debe importar manualmente, sobrepasa el límite de cantidad de medios de pago.';