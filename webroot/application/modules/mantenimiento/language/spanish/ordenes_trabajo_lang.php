<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Archivo de lenguajes en español de la clase de Ordenes de Trabajo.
*
* @author Santiago Arboleda Londoño <msarboleda@estradavelasquez.com>
* @copyright 2018 CI Estrada Velasquez y Cia S.A.S
*/

// Index
$lang['index_heading']                          = 'Listar ordenes de trabajo';

// View work order
$lang['vwo_heading']                            = 'Visualizar orden de trabajo';
$lang['vwo_wo_description_label']               = 'Descripción de la orden de trabajo';
$lang['vwo_work_type_label']                    = 'Tipo de trabajo';
$lang['vwo_task_label']                         = 'Tarea a realizar';
$lang['vwo_maintenance_technician_label']       = 'Técnico de la tarea';
$lang['vwo_basic_info_title']                   = 'Información básica';
$lang['vwo_detailed_info_title']                = 'Información detallada';
$lang['vwo_tasks_assigment_title']              = 'Asignación de tareas';
$lang['vwo_assigned_tasks_title']               = 'Tareas asignadas';
$lang['vwo_actions_title']                      = 'Acciones';
$lang['vwo_historical_title']                   = 'Histórico';
$lang['successfully_assigned_task']             = 'Se ha asignado la tarea al técnico de mantenimiento satisfactoriamente.';
$lang['successfully_wo_info_updated']           = 'Se ha actualizado la información de la orden de trabajo satisfactoriamente.';

// Generate work order from maintenance request (xhr)
$lang['generate_work_order_from_mr_success']    = 'Se generó la orden de trabajo con el código <b>%s</b> correctamente.';

// Ordenes_trabajo_model
$lang['generate_work_order_from_mr_error']      = 'No se pudo crear la orden de trabajo. Informe a Sistemas.';
$lang['get_work_order_no_results']              = 'La orden de trabajo no existe.';
$lang['get_all_maintenance_types_no_results']   = 'La consulta de los tipos de mantenimiento no obtuvo resultados.';
$lang['get_all_work_orders_no_results']         = 'La consulta de las ordenes de trabajo no obtuvo resultados.';
$lang['tech_work_orders_no_results']            = 'No hay órdenes de trabajo asignadas.';
$lang['get_work_orders_details_no_results']     = 'Aún no hay tareas asignadas para la orden de trabajo.';
$lang['get_all_work_types_no_results']          = 'La consulta de los tipos de trabajos no obtuvo resultados.';
$lang['get_work_orders_history_no_results']     = 'Aún no hay eventos en el histórico para las ordenes de trabajo.';
$lang['get_assigned_tasks_no_results']          = 'No se pudieron obtener las tareas finalizadas de la orden de trabajo.';
$lang['get_work_order_costs_no_results']        = 'La consulta de costos de la orden de trabajo no obtuvo resultados';
$lang['get_linked_work_orders_error']           = 'No hay de ordenes de trabajos vinculadas para la solicitud de mantenimiento.';
$lang['created_work_order_concept']             = 'Se ha creado la orden de trabajo.';
$lang['updated_work_order_concept']             = 'Se ha actualizado la descripción de la orden de trabajo. "<b>%s</b>"';
$lang['assigned_task_work_order_concept']       = 'Se ha asignado la tarea "<b>%s</b>" a un técnico de mantenimiento.';
$lang['started_work_order_concept']             = 'Se ha iniciado de la orden de trabajo.';
$lang['completed_work_order_concept']           = 'Se ha completado satisfactoriamente la orden de trabajo.';
$lang['conclusion_task_work_order_concept']     = 'Se ha completado satisfactoriamente una tarea de la orden de trabajo.';
$lang['not_established_work_order_concept']     = 'No se ha establecido un concepto válido para esta orden de trabajo.';
$lang['successfully_conclusion_task']           = 'Se ha concluido la tarea satisfactoriamente.';
$lang['successfully_started_work_order']        = 'Se ha iniciado la orden de trabajo satisfactoriamente.';
$lang['successfully_finished_work_order']       = 'Se ha finalizado la orden de trabajo satisfactoriamente.';
$lang['not_successfully_conclusion_task']       = 'Ocurrió un problema al concluir la tarea, comuniquese con el área de Sistemas.';
$lang['not_successfully_start_work_order']      = 'Ocurrió un problema al iniciar la orden de trabajo, comuníquese con el área de Sistemas.';
$lang['not_successfully_finish_work_order']     = 'Ocurrió un problema al finalizar la orden de trabajo, comuníquese con el área de Sistemas.';
$lang['unfinished_works']                       = 'Aún tienes trabajos pendientes por realizar, por favor realízalos y luego reporta su conclusión para finalizar la orden de trabajo.';
$lang['_sql_transaction_error']                 = '<b>Evento:</b> DB error. Comuniquese con el área de Sistemas.<br><b>Clase:</b> %s<br><b>Función:</b> %s<br><b>Código de error:</b> %s<br><b>Mensaje de error:</b> %s';
