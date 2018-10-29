$(document).ready(function() {
  var app_url = document.location.origin + '/';
  var complete_app_url = window.location.href;
  var $btn_generate_work_order = $('#generate_work_order_btn');
  var maint_request_code = complete_app_url.substr(complete_app_url.lastIndexOf('/') + 1);

  $btn_generate_work_order.on('click', function() {
    swal.mixin({
      showCancelButton: true,
      confirmButtonClass: 'btn btn-success',
      cancelButtonClass: 'btn btn-danger',
      buttonsStyling: false,
      confirmButtonText: 'Siguiente &rarr;',
      cancelButtonText: 'Cancelar',
      progressSteps: ['1', '2', '3']
    }).queue([
      {
        title: 'Tipo de mantenimiento',
        input: 'select',
        inputOptions: return_maintenance_types(),
        inputPlaceholder: 'Seleccione el tipo de mantenimiento',
        inputValidator: (value) => {
          return !value && 'Debes seleccionar un tipo de mantenimiento para la orden de trabajo.';
        }
      },
      {
        title: 'Encargado',
        input: 'select',
        inputOptions: return_maintenance_technicians(),
        inputPlaceholder: 'Seleccione el encargado',
        inputValidator: (value) => {
          return !value && 'Debes seleccionar un encargado para la orden de trabajo.';
        }
      },
      {
        title: 'Descripcion',
        input: 'textarea',
        inputPlaceholder: '¿Cuales son las tareas que se deben ejecutar en esta orden de trabajo?',
        inputValidator: (value) => {
          return !value && 'Debes escribir una descripción para la orden de trabajo.'
        }
      }
    ]).then((response) => {
      if (response.value) {
        var data = response.value;

        var confirmWorkOrderButtons = swal.mixin({
          confirmButtonClass: 'btn btn-success',
          cancelButtonClass: 'btn btn-danger',
          buttonsStyling: false,
        });

        confirmWorkOrderButtons({
          title: '¿Estás seguro?',
          text: 'Las ordenes de trabajo no se pueden eliminar, debe estar seguro a la hora de generarlas.',
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sí, generar orden de trabajo!',
          cancelButtonText: 'No, cancelar!',
          reverseButtons: true
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: app_url+'mantenimiento/ordenes_trabajo/xhr_generate_work_order_from_maintenance_request',
              type: 'post',
              dataType: 'json',
              data: {
                maint_request_code: maint_request_code,
                maint_technician: data[1],
                maint_type: data[0],
                maint_description: data[2]
              }
            }).done(function(response) {
              if (response.has_error) {
                swal({
                  title: 'Error!',
                  html: response.message,
                  type: 'error'
                });
              } else if (response.work_order_code) {
                swal({
                  title: 'Generada!',
                  text: response.message,
                  type: 'success',
                  html: 'En caso de que quieras ir directo a ver la orden de trabajo, ' +
                  'puedes hacer clic aquí. <br><a class="color-primary" href=" ' +
                  app_url + 'mantenimiento/ordenes_trabajo/view_work_order/' + maint_request_code + '">' +
                  ' <i class="fa fa-external-link"></i> Ver orden de trabajo</a>',
                  confirmButtonText: 'No, seguir viendo la solicitud'
                }).then(function() {
                  location.reload();
                });
              }
            }).fail(function() {
              swal({
                title: 'Error!',
                text: 'No se puede localizar el recurso para generar la orden de trabajo. Informe a Sistemas.',
                type: 'error'
              });
            });
          } else if (result.dismiss === swal.DismissReason.cancel) {
            swal({
              title: 'Cancelada!',
              text: 'No se generó orden de trabajo.',
              type: 'error'
            });
          }
        });
      }
    })
  });
});


/**
 * Devuelve todos los tipos de mantenimiento de acuerdo con el formato
 * de los input 'select' de SweetAlert 2.
 *
 */
async function return_maintenance_types() {
  let app_url = document.location.origin + '/';

  try {
    var result = await get_all_maintenance_types();
    var new_result = {};

    result.forEach(function(obj) {
      new_result[obj.idTipoMantenimiento] = obj.Descripcion;

      // No se debe listar el tipo de mantenimiento preventivo
      if (obj.idTipoMantenimiento === 1) {
        delete new_result[obj.idTipoMantenimiento];
      }
    });

    return new_result;
  } catch (err) {
    return swal({
      text: err.message,
      imageUrl: app_url+'assets/themes/elaadmin/emojipedia/pensive-face.png',
    });
  }
}

/**
 * Devuelve todos los técnicops de mantenimiento de acuerdo con el formato
 * de los input 'select' de SweetAlert 2.
 *
 */
async function return_maintenance_technicians() {
  let app_url = document.location.origin + '/';

  try {
    var result = await get_all_maintenance_technicians();
    var new_result = {};

    result.forEach(function(obj) {
      new_result[obj.usuario] = obj.nombre_usuario;
    });

    return new_result;
  } catch (err) {
    return swal({
      text: err.message,
      imageUrl: app_url+'assets/themes/elaadmin/emojipedia/pensive-face.png',
    });
  }
}
