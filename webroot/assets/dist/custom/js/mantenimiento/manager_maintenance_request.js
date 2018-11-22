$(document).ready(function() {
  // Pickadate.js definition
  var $planned_date_input = $('#planned_date'),
    $planned_time_input = $('#planned_time');

  $planned_date_input.pickadate({ formatSubmit: 'yyyy/mm/dd', hiddenName: true });
  $planned_time_input.pickatime({ interval: 15, formatSubmit: 'HH:i', hiddenName: true });

  // Select2 definition
  var $planned_asset = $('#planned_asset');

  $planned_asset.select2({ placeholder: 'Selecciona un activo', allowClear: true });
});
