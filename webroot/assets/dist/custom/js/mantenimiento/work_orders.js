/**
 * Obtiene todos los tipos de mantenimiento existentes.
 *
 */
function get_all_maintenance_types() {
  let app_url = document.location.origin + '/';

  var maintenanceTypesOptionsPromise = new Promise(function (resolve, reject) {
    $.getJSON(app_url+'mantenimiento/ordenes_trabajo/xhr_get_all_maintenance_types').done(function(response) {
      if (response.content) {
        reject(response);
      } else {
        resolve(response);
      }
    }).fail(function() {
      reject(new Error('No se puede localizar el recurso para cargar los tipos de mantenimiento.'));
    });
  });

  return maintenanceTypesOptionsPromise;
}
