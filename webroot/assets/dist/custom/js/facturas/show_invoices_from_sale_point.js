const base_url = document.location.origin+'/';

var $btn_clear_tables = $('#clear_tables');

$btn_clear_tables.on('click', function(event) {
  $.ajax({
    url: base_url+'facturas/importacion_facturas/delete_all_data_from_import_tables',
    type: 'POST',
    success: function() {
      swal({
        text: 'Las tablas de importación se vaciaron.',
        imageUrl: base_url+'assets/themes/elaadmin/emojipedia/smiling-face.png',
      });
    }
  })
});