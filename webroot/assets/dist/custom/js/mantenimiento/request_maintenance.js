$(document).ready(function() {
  // Pickadate.js definition
  var $damage_date_input = $('#damage_date'),
    $damage_time_input = $('#damage_time');

  $damage_date_input.pickadate({ max: new Date(), formatSubmit: 'yyyy/mm/dd', hiddenName: true });
  $damage_time_input.pickatime({ interval: 15, formatSubmit: 'HH:i', hiddenName: true });

  // Select2 definition
  var $damaged_asset = $('#damaged_asset');

  $damaged_asset.select2({ placeholder: 'Selecciona un activo', allowClear: true });
});
