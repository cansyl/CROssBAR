    function split( val ) {
	  return val.split(  '|' );
    }
    $( ".autocomplete" )
      .on( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        minLength: 2,
        source: function( request, response ) {
			$.ajax( {
			  url: "autocomplete.php",
			  dataType: "json",
			  data: {
				term: request.term,
				id: $(this)[0].element[0].id,
				tax: $('#tax_ids_multi').val(),
				rew: $('#only_reviewed').prop('checked')
			  },
			  success: function( data ) {
				response( data );
			  }
			} );
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( ' | ' );
          return false;
        }
      });