	$("#searchform").on( "submit", function( event ) {
		event.preventDefault();
		
		var parameters = $(this).serialize();
		console.log(parameters);
		/*
		$.ajax({
			type:"POST",
			url:'input_validation.php',
			data: $(this).serialize(),
			success: function(validate){
				switch(validate){
					case 'Disease Error':
					case 'Pathway Error':
					case 'Drug Error':
					case 'HPO Error':
					case 'Protein Error':
						
					break;
					
					default:
						console.log(validate);
					break;
				}
			}
		});
		*/
	});

	$('.bulk_task').on('click', function(){
		window.location.assign('bulk_if.php?task='+this.id);
	});
	
	$('#task_file').on('change',function(){
		var fileName = $(this).val();
		var cleanFileName = fileName.replace('C:\\fakepath\\', " ");
		$(this).next('.custom-file-label').html(cleanFileName);
	});