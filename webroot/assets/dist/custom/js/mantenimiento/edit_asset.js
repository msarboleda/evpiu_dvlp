$(document).ready(function() {
  // Definition form controls vars
  var $form = $('#edit_asset-form');
  var $clasif_select = $('#clasif_sel');
  var $resp_select = $('#resp_sel');
  var $est_select = $('#est_sel');
  var $plant_select = $('#plant_sel');
  var $prior_select = $('#prior_sel');

  $clasif_select.select2();
  $resp_select.select2();
  $est_select.select2({ minimumResultsForSearch: Infinity });
  $plant_select.select2({ minimumResultsForSearch: Infinity });
  $prior_select.select2({ minimumResultsForSearch: Infinity });
});