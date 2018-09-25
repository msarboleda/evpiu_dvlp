$(document).ready(function() {
  // Definition form controls vars
  var $clasif_select = $('#clasif_sel');
  var $resp_select = $('#resp_sel');
  var $est_select = $('#est_sel');
  var $plant_select = $('#plant_sel');
  var $prior_select = $('#prior_sel');

  // Pickadate.js definition
  var $ult_revis_date_input = $('#ult_revis');
  $ult_revis_date_input.pickadate({ max: new Date() });

  $clasif_select.select2({ placeholder: 'Selecciona una clasificación', allowClear: true });
  $resp_select.select2({ placeholder: 'Selecciona un responsable', allowClear: true });
  $est_select.select2({ placeholder: 'Selecciona un estado', allowClear: true, minimumResultsForSearch: Infinity });
  $plant_select.select2({ placeholder: 'Selecciona una planta', allowClear: true, minimumResultsForSearch: Infinity });
  $prior_select.select2({ placeholder: 'Selecciona una prioridad', allowClear: true, minimumResultsForSearch: Infinity });
});
