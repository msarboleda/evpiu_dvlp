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
$lang['view_mr_card_second_title'] = 'Comentarios de la solicitud';
$lang['view_mr_card_third_title'] = 'Acciones';
$lang['view_mr_card_fourth_title'] = 'Últimos eventos';
$lang['assigned_work_orders_title'] = 'Ordenes de trabajo asignadas';
$lang['view_mr_comments_placeholder'] = 'Si necesitas adjuntar algún comentario a la solicitud de mantenimiento, aquí lo puedes hacer...';
$lang['view_mr_update_submit_button'] = 'Enviar comentario';

// Send new work order email notification
$lang['wo_email_notification_not_sended'] = 'Ocurrió un error al enviar la notificación de orden de trabajo para una solicitud de mantenimiento.';

// Send new comment email notification
$lang['comment_email_notification_not_sended'] = 'Ocurrió un error al enviar la notificación de comentario para una solicitud de mantenimiento.';

// Solicitudes_model
$lang['add_rm_success'] = 'La solicitud de mantenimiento con el código <b>%s</b> se ha creado correctamente.';
$lang['add_rm_error'] = 'No se pudo crear la solicitud de mantenimiento.';
$lang['update_mr_error'] = 'No se pudo actualizar la solicitud de mantenimiento.';
$lang['get_all_maintenance_requests_no_results'] = 'La consulta de solicitudes de mantenimiento no obtuvo resultados.';
$lang['get_user_maintenance_requests_no_results'] = 'La consulta de tus solicitudes de mantenimiento no obtuvo resultados.';
$lang['get_maintenance_request_no_results'] = 'La solicitud de mantenimiento no existe.';
$lang['get_maintenance_request_history_no_results'] = 'No se ha logrado obtener el histórico de esta solicitud de mantenimiento.';
$lang['get_event_no_results'] = 'La consulta del evento no obtuvo resultados.';
$lang['created_maint_request_event'] = 'Se ha creado la solicitud de mantenimiento.';
$lang['com_added_maint_request_event'] = 'Se ha realizado un comentario para esta solicitud de mantenimiento: "<b>%s</b>"';
$lang['wo_created_work_order_event'] = 'Se ha creado una orden de trabajo para esta solicitud de mantenimiento con el código <b>%s</b>.';
$lang['wo_finished_work_order_event'] = 'Se ha finalizado la orden de trabajo con el código <b>%s</b>.';
$lang['started_maint_request_event'] = 'Se ha comenzado a trabajar con el activo de la solicitud de mantenimiento.';
$lang['completed_maint_request_event'] = 'Se ha completado satisfactoriamente la solicitud de mantenimiento.';
$lang['approved_maint_request_event'] = 'Se ha aprobado la solicitud de mantenimiento.';
$lang['canceled_maint_request_event'] = 'Se ha anulado la solicitud de mantenimiento.';
$lang['concept_not_established_maint_request'] = 'No se ha establecido un concepto válido para esta solicitud de mantenimiento.';
$lang['add_event_to_history_error'] = 'No se pudo añadir el evento al histórico de la solicitud de mantenimiento.';
$lang['unfinished_work_orders']                    = 'Aún tienes órdenes de trabajo pendientes por realizar, por favor realízalas y luego puedes finalizar la solicitud de mantenimiento.';
