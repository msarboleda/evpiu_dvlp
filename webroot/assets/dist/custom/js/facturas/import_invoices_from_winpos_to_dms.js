const base_url = document.location.origin+'/';

var $invoices_date_input = $('#invoices_date'),
    $invoices_date_datepicker = $invoices_date_input.pickadate({ max: new Date() }),
    invoice_date__selected = $invoices_date_datepicker.pickadate('picker');

var $btn_pick_terminal = $('button.pick-terminal');
var $btn_check_invoices = $('#check_invoices');
var terminal;

$btn_pick_terminal.on('click', function(event) {
  terminal = $(this).val();

  $('.selected i.fa').addClass('fa-square-check-o').removeClass('fa-check-square');
  $('.selected').removeClass('selected');
  $(this).addClass('selected');
  $(this).find('i.fa').addClass('fa-check-square');

  if (invoice_date__selected.get('open')) {
    invoice_date__selected.close();
  } else {
    invoice_date__selected.open();
  }

  event.stopPropagation();
});

$btn_check_invoices.on('click', function() {
  var fecha = invoice_date__selected.get('select', 'yyyy-dd-mm');
  window.location.replace(base_url+'facturas/importacion_facturas/show_invoices_from_sale_point_on_date?t='+terminal+'&d='+fecha);
});

