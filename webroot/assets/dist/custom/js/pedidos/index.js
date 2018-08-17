$(document).ready(function() {
	const base_url = document.location.origin+'/';
	const $tblPedidos = $('#pedidos');
  
 const Pedidos = $tblPedidos.DataTable({
	  ajax: {
	    url: base_url+'pedidos/xhr/xhr_get_All_Pedidos',
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
      { data: 'NroPedido' },
      { data: 'FechaPedido' },
      { data: 'OrdenCompra' },
      { data: 'CodCliente' },
      { data: 'RazonSocial' },
      { data: 'CodVendedor' },
      { data: 'NomVendedor' },
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