$(document).ready(function() {
	const base_url = document.location.origin+'/';
	const $assetsTable = $('#activos');
  
  const Activos = $assetsTable.DataTable({
	  ajax: {
	    url: base_url+'mantenimiento/activos/xhr_get_all_assets',
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
      { 
        data: 'CodActivo',
        render: function ( data, type, row ) {
          return '<a href="'+base_url+'mantenimiento/activos/view_asset?ac='+row.CodActivo+'">'+row.CodActivo+' <i class="fa fa-external-link"></i></a>'
        }
      },
      { data: 'NomActivo' },
      { data: 'NomPlanta' },
      { data: 'NomClasificacion' },
      { data: 'NomResponsable' },
      { data: 'NomEstado' },
      { data: 'NomPrioridad' },
      { data: 'UltimaRevision' },
      {
        data: null,
        render: function ( data, type, row ) {
          return '<a href="'+base_url+'mantenimiento/activos/edit_asset/'+row.CodActivo+'" class="btn btn-sm btn-primary">Editar</a>';
        }
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
    ]
	});    
});