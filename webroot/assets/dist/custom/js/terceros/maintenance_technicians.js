/**
 * Obtiene todos los tipos de mantenimiento existentes.
 *
 */
function get_all_maintenance_technicians() {
  let app_url = document.location.origin + '/';

  var maintenanceTechniciansPromise = new Promise(function (resolve, reject) {
    $.getJSON(app_url+'terceros/usuarios/xhr_get_all_maintenance_technicians').done(function(response) {
      if (response.content) {
        reject(response);
      } else {
        resolve(response);
      }
    }).fail(function() {
      reject(new Error('No se puede localizar el recurso para cargar los técnicos de mantenimiento.'));
    });
  });

  return maintenanceTechniciansPromise;
}
