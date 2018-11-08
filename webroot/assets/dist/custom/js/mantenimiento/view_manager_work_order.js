$(document).ready(function() {
  var app_url = document.location.origin + '/';
  var complete_app_url = window.location.href;
  var $btnReportConclusion = $('.report-conclusion');
  var $btnStartWorkOrder = $('.start-work-order');
  var $btnFinishWorkOrder = $('.finish-work-order');
  var work_order_code = complete_app_url.substr(complete_app_url.lastIndexOf('/') + 1);

  $btnReportConclusion.on('click', function(e) {
    e.preventDefault();

    var item = $(this).attr('data-item');

    swal.mixin({
      showCancelButton: true,
      confirmButtonClass: 'btn btn-success',
      cancelButtonClass: 'btn btn-danger',
      buttonsStyling: false,
      confirmButtonText: 'Siguiente &rarr;',
      cancelButtonText: 'Cancelar',
      progressSteps: ['1', '2'],
      allowOutsideClick: false
    }).queue([
      {
        title: 'Acción realizada',
        input: 'textarea',
        inputPlaceholder: 'Detalla las actividades realizadas',
        inputValidator: (value) => {
          return !value && 'Debes detallar las actividades realizadas.'
        }
      },
      {
        title: 'Costo',
        input: 'text',
        inputPlaceholder: 'Ingresa el costo de la tarea realizada',
        inputValidator: (value) => {
          return !value && 'Debes proporcionar un costo para la tarea.';
        }
      }
    ]).then((result) => {
      if (result.value) {
        var data = result.value;

        $.ajax({
          url: app_url+'mantenimiento/ordenes_trabajo/xhr_report_task_conclusion',
          type: 'post',
          dataType: 'json',
          data: {
            wo_code: work_order_code,
            task_id: item,
            task_description: data[0],
            task_cost: data[1]
          }
        }).done(function(response) {
          if (response.success === true) {
            swal({
              title: 'Tarea concluida!',
              text: response.message,
              type: 'success'
            }).then(function() {
              location.reload();
            });
          }

          if (response.success === false) {
            swal({
              title: 'Error!',
              html: response.message,
              type: 'error'
            });
          }
        }).fail(function() {
          swal({
            title: 'Error',
            text: 'No se ha podido reportar la conclusión. Informe a Sistemas.',
            type: 'error'
          });
        });
      }
    })
  });

  $btnStartWorkOrder.on('click', function(e) {
    e.preventDefault();

    $(this).text('Iniciando orden de trabajo...');
    $(this).attr('disabled', true);

    $.ajax({
      url: app_url+'mantenimiento/ordenes_trabajo/xhr_start_work_order',
      type: 'post',
      dataType: 'json',
      data: {
        wo_code: work_order_code,
      }
    }).done(function(response) {
      if (response.success === true) {
        swal({
          title: 'Orden de trabajo iniciada',
          text: response.message,
          type: 'success'
        }).then(function() {
          location.reload();
        });
      }

      if (response.success === false) {
        swal({
          title: 'Error!',
          html: response.message,
          type: 'error'
        });
      }
    }).fail(function() {
      swal({
        title: 'Error',
        text: 'No se ha podido iniciar la orden de trabajo. Informe a Sistemas.',
        type: 'error'
      });
    });
  });

  $btnFinishWorkOrder.on('click', function(e) {
    e.preventDefault();

    $(this).text('Finalizando orden de trabajo...');
    $(this).attr('disabled', true);

    $.ajax({
      url: app_url+'mantenimiento/ordenes_trabajo/xhr_finish_work_order',
      type: 'post',
      dataType: 'json',
      data: {
        wo_code: work_order_code,
      }
    }).done(function(response) {
      if (response.success === true) {
        swal({
          title: 'Orden de trabajo finalizada',
          text: response.message,
          type: 'success'
        }).then(function() {
          location.reload();
        });
      }

      if (response.success === false) {
        swal({
          title: 'Error!',
          html: response.message,
          type: 'error'
        }).then(function() {
          location.reload();
        });
      }
    }).fail(function() {
      swal({
        title: 'Error',
        text: 'No se ha podido iniciar la orden de trabajo. Informe a Sistemas.',
        type: 'error'
      }).then(function() {
        location.reload();
      });
    });
  });
});
