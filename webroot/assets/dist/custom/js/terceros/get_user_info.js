/**
 * Obtiene el nombre de usuario actual de la plataforma.
 *
 */
function get_current_username() {
  let app_url = document.location.origin + '/';

  const promise = new Promise(function (resolve, reject) {
    $.ajax({
      url: app_url+'terceros/usuarios/xhr_get_current_username',
      type: 'post',
      dataType: 'json',
      success: function(response) {
        resolve(response);
      },
      error: function() {
        reject(new Error('No se pudo obtener el usuario de la plataforma.'));
      }
    });
  });

  return promise;
}

/**
 * Procesa el nombre de usuario actual de la plataforma
 * y lo devuelve.
 */
async function process_current_username() {
  let app_url = document.location.origin + '/';

  try {
    const result = await get_current_username();
    return result;
  } catch (err) {
    return swal({
      text: err.message,
      imageUrl: app_url+'assets/themes/elaadmin/emojipedia/pensive-face.png',
    });
  }
}


