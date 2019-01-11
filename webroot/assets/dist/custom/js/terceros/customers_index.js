$(document).ready(function() {
  const base_url = document.location.origin+'/';
  const $my_clients_table = $('#my_clients');

  const my_clients = $my_clients_table.DataTable({
	  ajax: {
	    url: base_url+'xhr_clientes/xhr_getCustomersDataFromCurrentVendor',
	    method: 'POST',
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
        data: null,
        render: function (data, type, full, meta) {
          return meta.row + 1;
        }
      },
      { data: 'CUSTID_23' },
      { data: 'NAME_23' },
      { data: 'UDFKEY_23' },
      {
        data: null,
        render: function (data) {
          return data.ADDR1_23+' '+data.ADDR2_23;
        }
      },
      {
        data: null,
        render: function (data) {
          return data.PHONE_23+' '+data.TELEX_23;
        }
      },
      {
        data: 'STATUS_23',
        render: function (data) {
          if (data === 'R') {
            return '<span class="badge badge-success">LIBERADO</span>';
          }

          return '<span class="badge badge-danger">RETENIDO</span>';
        }
      },
      {
        data: null,
        render: function ( data, type, row ) {
          return '<a href="'+base_url+'terceros/clientes/update_customer/'+row.CUSTID_23+'" class="btn btn-sm btn-primary">Editar</a>';
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
