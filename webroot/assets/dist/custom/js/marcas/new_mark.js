$(document).ready(function() {
  // Definition vars
  const $form = $('#new_mark-form');

  $form.on('submit', function(event) {
    if ($(this)[0].checkValidity() === false) {
      event.preventDefault();
      event.stopPropagation();
    }

    $form.addClass('was-validated');
  });
});