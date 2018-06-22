var Requerimientos;
const column_pos = 4;

$.fn.dataTable.ext.search.push(function (settings, data) { 
 	var statusReq = data[column_pos] || "";
  var filterVal = $("#status_filter").val();

  if (filterVal != '*') {
    if (statusReq == filterVal) {
    	return true;
    }       
    else {
    	return false;
    }   
  } else {
  	return true;
  }
});

$('#status_filter').change(function () {
  Requerimientos.draw();
});  
 

$(document).ready(function() {
	const base_url = document.location.origin+'/';
	const $reqsTable = $('#requerimientos');
  
  Requerimientos = $reqsTable.DataTable({
	  ajax: {
	    url: base_url+'requerimientos/xhr/xhr_get_Requerimientos_by_current_vendor',
	    method: 'POST',
	    dataSrc: function(response) {
	      return response;
	    }
	  },
	  columns: [
      { 
        data: 'NroRequerimiento',
        render: function (data, type, row, meta) {
          return '<a class="btn btn-link" href="'+base_url+'requerimientos/view_requerimiento/'+data+'">'+data+'</a>';
        }
      },
      { data: 'NomCliente' },
      { data: 'NomMarca' },
      { data: 'DescPrimaria' },
      { data: 'NomEstado' },
      { data: 'FechaCreacion' },
      { data: 'NomDisenador' }
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