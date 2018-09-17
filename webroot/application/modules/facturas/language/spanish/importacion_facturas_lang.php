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

// Generate manual invoice structure
$lang['manual_invoice_error'] = 'Esta factura se debe importar manualmente, sobrepasa el límite de cantidad de medios de pago.';