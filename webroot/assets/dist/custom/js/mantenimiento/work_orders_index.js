$(document).ready(function() {
	const base_url = document.location.origin+'/';
	const $workOrdersTable = $('#ordenest');

  $workOrdersTable.DataTable({
	  ajax: {
	    url: base_url+'mantenimiento/ordenes_trabajo/xhr_get_all_work_orders',
	    method: 'POST',
	    dataSrc: function(response) {
        if (response.message) {
          swal({
            text: response.message,
            imageUrl: base_url+'assets/themes/elaadmin/emojipedia/pensive-face.png',
          });
        }

	      return response;
	    }
    },
	  columns: [
      { data: 'CodOt' },
      {
        data: 'NomEncargado',
        orderable: false
      },
      {
        data: 'NomTipoMantenimiento',
        orderable: false
      },
      {
        data: 'NomEstado',
        orderable: false
      },
      { data: 'BeautyCreationDate' },
      {
        data: 'NomCreo',
        orderable: false
      },
      {
        data: null,
        render: function ( data, type, row ) {
          return '<a href="'+base_url+'mantenimiento/ordenes_trabajo/view_work_order/'+row.CodOt+'" class="btn btn-sm btn-primary">Visualizar</a>';
        },
        orderable: false
      }
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
    ],
    initComplete: function () {
      this.api().columns([1, 2, 3]).every( function () {
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
