$(document).ready(function() {
	const app_url = document.location.origin+'/';
	var $maintRequests = $('#solicitudes');

  $maintRequests.DataTable({
	  ajax: {
	    url: app_url+'mantenimiento/solicitudes/xhr_get_all_maintenance_requests',
	    method: 'POST',
	    dataSrc: function(response) {
        if (response.exception) {
          swal({
            text: response.exception,
            imageUrl: app_url+'assets/themes/elaadmin/emojipedia/pensive-face.png',
          });
        }

	      return response;
	    }
    },
	  columns: [
      { data: 'CodSolicitud' },
      { data: 'NomActivo' },
      { data: 'NomSolicitante' },
      { data: 'BeautyDamageDate' },
      { data: 'BeautyRequestDate' },
      { data: 'NomEstado' },
      {
        data: null,
        render: function ( data, type, row ) {
          return '<a href="'+app_url+'mantenimiento/solicitudes/view_maint_request/'+row.CodSolicitud+'" class="btn btn-sm btn-primary"> Visualizar</a>';
        }
      }
    ],
	  language: {
	    url: app_url+'assets/themes/elaadmin/js/lib/datatables/spanish.json'
	  },
	  dom: 'B<"row">lfrtip',
	  buttons: [
      {
        text: '<i class="fa fa-refresh"></i> Actualizar',
        action: function ( e, dt, node, config ) {
          dt.ajax.reload();
        }
      }
    ],
    initComplete: function () {
      this.api().columns([1, 2, 5]).every( function () {
        var column = this;
        var select = $('<select><option value="">Mostrar todo</option></select>')
            .appendTo( $(column.footer()).empty() )
            .on( 'change', function () {
              var val = $.fn.dataTable.util.escapeRegex(
                $(this).val()
              );

              column
                .search( val ? '^'+val+'$' : '', true, false )
                .draw();
            });

        column.data().unique().sort().each( function ( d, j ) {
          select.append( '<option value="'+d+'">'+d+'</option>' );
        });
      });
    },
    order: [[0, 'desc']]
	});
});
