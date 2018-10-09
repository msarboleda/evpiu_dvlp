<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Archivo de lenguajes en español de la clase de Solicitudes.
*
* @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
* @copyright 2018 CI Estrada Velasquez y Cia S.A.S
*/

// Index
$lang['index_heading'] = 'Listar solicitudes de mantenimiento';

// New request maintenance
$lang['new_rm_heading'] = 'Solicitar mantenimiento';
$lang['new_rm_damage_date_label'] = 'Fecha del daño';
$lang['new_rm_damage_time_label'] = 'Hora del daño';
$lang['new_rm_damaged_asset_label'] = 'Activo afectado';
$lang['new_rm_damage_description_label'] = 'Descripción del problema';
$lang['new_rm_submit_button'] = 'Solicitar mantenimiento';

// ** Help area **
$lang['new_rm_damage_date_help'] = 'Debe seleccionar la fecha en la que se produjo el daño del equipo.';
$lang['new_rm_damage_time_help'] = 'Debe seleccionar la hora en la que se produjo el daño del equipo.';
$lang['new_rm_damaged_asset_help'] = 'Debe seleccionar el equipo al que se produjo el incidente.';
$lang['new_rm_damage_description_help'] = 'Escriba una breve descripción del incidente ocurrido al equipo seleccionado.';

// View maintenance request
$lang['view_mr_heading'] = 'Visualizar solicitud de mantenimiento';
$lang['view_mr_card_first_title'] = 'Información básica';
// Solicitudes_model
$lang['add_rm_success'] = 'La solicitud de mantenimiento con el código <b>%s</b> se ha creado correctamente.';
$lang['add_rm_error'] = 'No se pudo crear la solicitud de mantenimiento.';
$lang['get_all_maintenance_requests_no_results'] = 'La consulta de solicitudes de mantenimiento no obtuvo resultados.';
$lang['get_user_maintenance_requests_no_results'] = 'La consulta de tus solicitudes de mantenimiento no obtuvo resultados.';
$lang['get_maintenance_request_no_results'] = 'La consulta de la solicitud de mantenimiento no obtuvo resultados.';
