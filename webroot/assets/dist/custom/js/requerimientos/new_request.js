// # Project URL #
const base_url = document.location.origin+'/';

$(document).ready(function() {
  $('html, body').animate({ scrollTop: 0 }, "slow");
  
	// Definition vars
	const $form = $('#new_request-form');
	const $enable_vendors_control = $('#enable_vendors');
	const $vendors_control = $('#Vendedor');
	const $customers_control = $('#Cliente');
	const $marks_control = $('#Marca');
	const $params_control = $('#Parametro');
	const $line_control = $('#Linea');
	const $subline_control = $('#Sublinea');
	const $features_control = $('#Caracteristica');
	const $materials_control = $('#Material');
	const $tams_control = $('#Tamano');
	const $thicknesses_control = $('#Espesor');
	const $reliefs_control = $('#Relieve');
	const $applied_art_control = $('#applied_art');
	const $base_product_control = $('#base_product');
	const $submit_button = $('#submit');

	// Collapsables Elements
	const $vendors_collapse = $('#vendors_collapse');
	const $base_product_collapse = $('#base_product_collapse');

	$enable_vendors_control.click(function() {
		$vendors_collapse.collapse('toggle');
	});

	$applied_art_control.click(function() {
		$base_product_collapse.collapse('toggle');

		if ($(this).is(':checked')) {
			$base_product_control.prop('required', true);
		} else {
			$base_product_control.prop('required', false);
		}
	});

	// Select2 Initialization Area
	$vendors_control.select2();
	$customers_control.select2();
  $line_control.select2();
 	$subline_control.select2();
 	$params_control.select2();
 	$features_control.select2();
 	$materials_control.select2();
 	$tams_control.select2();
 	$thicknesses_control.select2();
 	$reliefs_control.select2();
 	$base_product_control.select2();

 	// Disable Controls Area
	$line_control.attr('disabled', true);
	$subline_control.attr('disabled', true);
	$features_control.attr('disabled', true);
	$materials_control.attr('disabled', true);
	$tams_control.attr('disabled', true);
	$thicknesses_control.attr('disabled', true);
	$reliefs_control.attr('disabled', true);
	$applied_art_control.attr('disabled', true);

	// # Events #

  // Control de Asesores comerciales
	$vendors_control.change(function() {
		if ($(this).val()){
  		//

  		$('#Vendedor option:selected').each(function () {
        let vendor_control_value = $(this).val();

        $.ajax({
        	url: base_url+'requerimientos/xhr/xhr_Customers_from_Vendor_select',
        	type: 'POST',
        	data: { Vendedor: vendor_control_value }, 
        	beforeSend: function() {
        		if ($customers_control.is(':not(:disabled)')) {
	        		$customers_control.attr('disabled', true);
	       	 	}
        	},
        	success: function(response) {
        		if (response) {
        			$customers_control.html(response);
        			$customers_control.attr('disabled', false);
        		} 
        	}
        })
      });
  	} else {	
  		//
  		$('#Vendedor option:selected').each(function () {
        $.ajax({
        	url: base_url+'requerimientos/xhr/xhr_Customers_from_Current_Vendor_select',
        	type: 'POST',
        	beforeSend: function() {
        		if ($customers_control.is(':not(:disabled)')) {
	        		$customers_control.attr('disabled', true);
	        		$submit_button.attr('disabled', true);
	       	 	}
        	},
        	success: function(response) {
        		if (response) {
        			$customers_control.html(response);
        			$customers_control.attr('disabled', false);
        			$submit_button.attr('disabled', false);
        		} 
        	}
        })
      });
  	}
	});

  // Control de Marcas
  $marks_control.select2({
    ajax: {
    	url: base_url+'requerimientos/xhr/xhr_Marks_remote_select',
    	dataType: 'json',
    	delay: 1000,
    	data: function (params) {
	      return {
	        q: params.term // search term
	      };
	    },
	    processResults: function (data) {
        // parse the results into the format expected by Select2.
        // since we are using custom formatting functions we do not need to
        // alter the remote JSON data
        return { 
          results: data
      	}; 
      },
	    cache: true, 
  	},
  	minimumInputLength: 2,
    language: 'es'
  });

  // Control de Productos Base
  $base_product_control.select2({
    ajax: {
    	url: base_url+'requerimientos/xhr/xhr_Base_Products_remote_select',
    	dataType: 'json',
    	delay: 1000,
    	data: function (params) {
	      return {
	        q: params.term // search term
	      };
	    },
	    processResults: function (data) {
        // parse the results into the format expected by Select2.
        // since we are using custom formatting functions we do not need to
        // alter the remote JSON data
        return { 
          results: data
      	}; 
      },
	    cache: true, 
  	},
  	minimumInputLength: 2,
    language: 'es'
  });

	// Control de Parámetro
  $params_control.change(function() {
  	if ($(this).val()){
  		$line_control.attr('disabled', false);
  		$subline_control.attr('disabled', true);
  		$features_control.attr('disabled', true);
  		$materials_control.attr('disabled', true);
  		$tams_control.attr('disabled', true);
  		$thicknesses_control.attr('disabled', true);
  		$reliefs_control.attr('disabled', true);

  		$('#Parametro option:selected').each(function () {
        let param_control_value = $(this).val();

        $.ajax({
        	url: base_url+'requerimientos/xhr/xhr_Lines_select',
        	type: 'POST',
        	data: { Parametro: param_control_value}, 
        	beforeSend: function() {
        		if ($line_control.is(':not(:disabled)')) {
	        		$line_control.attr('disabled', true);
	        		$submit_button.attr('disabled', true);
	       	 	}
        	},
        	success: function(response) {
        		if (response) {
        			$line_control.html(response);
        			$line_control.attr('disabled', false);
        			$submit_button.attr('disabled', false);
        		} 
        	}
        })
      });
  	} else {	
  		$line_control.attr('disabled', true);
  		$subline_control.attr('disabled', true);
  		$features_control.attr('disabled', true);
  		$materials_control.attr('disabled', true);
  		$tams_control.attr('disabled', true);
  		$thicknesses_control.attr('disabled', true);
  		$reliefs_control.attr('disabled', true);
  	}
  });

  // Control de Líneas
  $line_control.change(function() {
  	if ($(this).val()){
  		$subline_control.attr('disabled', false);
  		$features_control.attr('disabled', true);
  		$materials_control.attr('disabled', true);
  		$tams_control.attr('disabled', true);
  		$thicknesses_control.attr('disabled', true);
  		$reliefs_control.attr('disabled', true);

  		$('#Linea option:selected').each(function () {
        let line_control_value = $(this).val();

        if ($(this).val() == '14'){
	        $applied_art_control.prop('disabled', false);
	      } else {
	        $applied_art_control.prop('disabled', true);
	        $applied_art_control.prop('checked', false);     
					$base_product_collapse.collapse('hide');
	      }

        $.ajax({
        	url: base_url+'requerimientos/xhr/xhr_Sublines_select',
        	type: 'POST',
        	data: { Linea: line_control_value}, 
        	beforeSend: function() {
        		if ($subline_control.is(':not(:disabled)')) {
	        		$subline_control.attr('disabled', true);
	        		$submit_button.attr('disabled', true);
	       	 	}
        	},
        	success: function(response) {
        		if (response) {
        			$subline_control.html(response);
        			$subline_control.attr('disabled', false);
        			$submit_button.attr('disabled', false);
        		} 
        	}
        })
      });
  	} else {
  		$subline_control.attr('disabled', true);
  		$features_control.attr('disabled', true);
  		$materials_control.attr('disabled', true);
  		$tams_control.attr('disabled', true);
  		$thicknesses_control.attr('disabled', true);
  		$reliefs_control.attr('disabled', true);
  	}
  });

  // Control de Sublíneas
  $subline_control.change(function() {
  	if ($(this).val()){
  		$features_control.attr('disabled', false);
  		$materials_control.attr('disabled', false);
  		$tams_control.attr('disabled', true);
  		$thicknesses_control.attr('disabled', true);
  		$reliefs_control.attr('disabled', true);

  		$('#Sublinea option:selected').each(function () {
  			let line_control_value = $line_control.val();
        let subline_control_value = $(this).val();

        $.ajax({
        	url: base_url+'requerimientos/xhr/xhr_Features_select',
        	type: 'POST',
        	data: { Linea: line_control_value, Sublinea: subline_control_value }, 
        	beforeSend: function() {
        		if ($features_control.is(':not(:disabled)')) {
	        		$features_control.attr('disabled', true);
	        		$submit_button.attr('disabled', true);
	       	 	}
        	},
        	success: function(response) {
        		if (response) {
        			$features_control.html(response);
        			$features_control.attr('disabled', false);
        			$submit_button.attr('disabled', false);
        		} 
        	}
        });

        $.ajax({
        	url: base_url+'requerimientos/xhr/xhr_Materials_select',
        	type: 'POST',
        	data: { Linea: line_control_value, Sublinea: subline_control_value }, 
        	beforeSend: function() {
        		if ($materials_control.is(':not(:disabled)')) {
	        		$materials_control.attr('disabled', true);
	        		$submit_button.attr('disabled', true);
	       	 	}
        	},
        	success: function(response) {
        		if (response) {
        			$materials_control.html(response);
        			$materials_control.attr('disabled', false);
        			$submit_button.attr('disabled', false);
        		} 
        	}
        });

        $.ajax({
        	url: base_url+'requerimientos/xhr/xhr_Reliefs_select',
        	type: 'POST',
        	data: { Linea: line_control_value, Sublinea: subline_control_value }, 
        	beforeSend: function() {
        		if ($reliefs_control.is(':not(:disabled)')) {
	        		$reliefs_control.attr('disabled', true);
	        		$submit_button.attr('disabled', true);
	       	 	}
        	},
        	success: function(response) {
        		if (response) {
        			$reliefs_control.html(response);
        			$reliefs_control.attr('disabled', false);
        			$submit_button.attr('disabled', false);
        		} 
        	}
        });
      });
  	} else {
  		$features_control.attr('disabled', true);
  		$materials_control.attr('disabled', true);
  		$tams_control.attr('disabled', true);
  		$thicknesses_control.attr('disabled', true);
  		$reliefs_control.attr('disabled', true);
  	}
  });

  // Control de Materiales
  $materials_control.change(function() {
  	if ($(this).val()){
  		$tams_control.attr('disabled', false);
  		$thicknesses_control.attr('disabled', false);

  		$('#Tamano option:selected').each(function () {
  			let line_control_value = $line_control.val();
        let subline_control_value = $subline_control.val();
        let material_control_value = $materials_control.val();

        $.ajax({
        	url: base_url+'requerimientos/xhr/xhr_Sizes_select',
        	type: 'POST',
        	data: { 
        		Linea: line_control_value, 
        		Sublinea: subline_control_value, 
        		Material: material_control_value 
        	}, 
        	beforeSend: function() {
        		if ($tams_control.is(':not(:disabled)')) {
	        		$tams_control.attr('disabled', true);
	        		$submit_button.attr('disabled', true);
	       	 	}
        	},
        	success: function(response) {
        		if (response) {
        			$tams_control.html(response);
        			$tams_control.attr('disabled', false);
        			$submit_button.attr('disabled', false);
        		} 
        	}
        });

        $.ajax({
        	url: base_url+'requerimientos/xhr/xhr_Thicknesses_select',
        	type: 'POST',
        	data: { Material: material_control_value }, 
        	beforeSend: function() {
        		if ($thicknesses_control.is(':not(:disabled)')) {
	        		$thicknesses_control.attr('disabled', true);
	        		$submit_button.attr('disabled', true);
	       	 	}
        	},
        	success: function(response) {
        		if (response) {
        			$thicknesses_control.html(response);
        			$thicknesses_control.attr('disabled', false);
        			$submit_button.attr('disabled', false);
        		} 
        	}
        });
      });
  	} else {
  		$tams_control.attr('disabled', true);
  		$thicknesses_control.attr('disabled', true);
  	}
  });

  $form.on('submit', function(event) {
    if ($(this)[0].checkValidity() === false) {
      event.preventDefault();
      event.stopPropagation();
      $('html, body').animate({ scrollTop: 0 }, "slow");
    }

    $form.addClass('was-validated');
  });
});
