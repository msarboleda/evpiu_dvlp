$(document).ready(function() {
	const base_url = document.location.origin+'/';
	const $marksTable = $('#marcas');
  
  const Marcas = $marksTable.DataTable({
	  ajax: {
	    url: base_url+'marcas/xhr/xhr_get_All_Marks',
	    method: 'GET',
	    dataSrc: function(response) {
        if (response.exception) {
          swal({
            text: response.exception,
            imageUrl: base_url+'assets/themes/elaadmin/emojipedia/pensive-face.png',
          });
        }

	      return response;
	    }
	  },
	  columns: [
      { data: 'CodMarca' },
      { data: 'NomMarca' },
      { 
        data: 'Generico',
        render: function (data, type, row, meta) {
          if (data === 1) {
            return 'Sí';
          }

          return 'No';
        }
      },
    ],
	  language: {
	    url: base_url+'assets/themes/elaadmin/js/lib/datatables/spanish.json'
	  },
	  dom: 'B<"row">lfrtip',
	  buttons: [
      {
        text: '<i class="fa fa-refresh"></i> Actualizar',
        action: function ( e, dt, node, config ) {
          dt.ajax.reload();
        }
      }
    ]
	});    
});