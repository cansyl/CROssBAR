$( function() {

	$('#network').height( screen.height - $('#top_menu').height() - 130 );

	$('#network').height( screen.height - $('#top_menu').height() - 130 );

    function split( val ) {
	  return val.split(  '|' );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }

 $( "#node_search" )
      .on( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        minLength: 1,
        source: function( request, response ) {
			$.ajax( {
			  url: "covid19/search_in_network_big.php",
			  dataType: "json",
			  data: {
				term: request.term
			  },
			  success: function( data ) {
				//console.log(data);
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
          terms.pop();
          this.value = terms.join( '' );
		  //console.log(ui.item.value);
		  apply_high(ui.item.value);
          return false;
        }
      });

	function apply_high(name){
		$.ajax( {
		  url: "covid19/take_id_of_node_big.php",
		  data: {
			name: name
		  },
		  success: function( data ) {

			var ele = cy.$('node[id = "'+data+'"]');
			var degree = 
			(ele.addClass('highlight')
				.outgoers()
				.union(ele.incomers())
				.length) / 2;

			$.ajax({
				type:"POST",
				url:'covid19/selected_node.php',
				data: {node:ele.data(), degree:degree},
				success: function(result){
					if($('#selected_nodes').hasClass('d-none')){
						$('#selected_nodes').append('<h5 class="row alert alert-secondary p-1 mb-0 border-bottom-0 border-secondary">Selected Nodes</h5>');
						$('#selected_nodes').removeClass('d-none');
					}
					$('#selected_nodes').append(result);
				}
			});

			ele.addClass('zelected');
			ele.connectedEdges().connectedNodes().addClass('highlightednode');
			ele.connectedEdges().animate({
				style: {width: "3px","opacity":"1","font-size": "30px"}
			});
			
		cy.elements()
			.removeClass('semitransp');
		ele.removeClass('highlight')
			.outgoers()
			.union(ele.incomers())
			.removeClass('highlight');
			
			
		  }
		} );
	}






	cy.on('tap', function(event){
		var evtTarget = event.target;
		if( evtTarget === cy ){
			$('#selected_nodes').html('');
			$('#selected_nodes').addClass('d-none');
			// if clicked on the empty area (canvas)
			cy.$('node').removeClass('highlightednode');
			cy.$('node').removeClass('zelected');
			cy.$('edge').forEach((ed) => {
				ed.animate({
					style: {width: "1px","opacity":"0.25","font-size": "0px"}
				});
			});
		}
	});

	cy.on('select', function(e){
		var ele = e.target;
		var degree = 
		(ele.addClass('highlight')
			.outgoers()
			.union(ele.incomers())
			.length) / 2;

		$.ajax({
			type:"POST",
			url:'covid19/selected_node.php',
			data: {node:ele.data(), degree:degree},
			success: function(result){
				if($('#selected_nodes').hasClass('d-none')){
					$('#selected_nodes').append('<h5 class="row alert alert-secondary p-1 mb-0 border-bottom-0 border-secondary">Selected Nodes</h5>');
					$('#selected_nodes').removeClass('d-none');
				}
				$('#selected_nodes').append(result);
			}
		});

		ele.addClass('zelected');
		ele.connectedEdges().connectedNodes().addClass('highlightednode');
		ele.connectedEdges().animate({
			style: {width: "3px","opacity":"1","font-size": "10px"}
		});
		
	});

	cy.on('mouseover', 'node', function(e) {
		var ele = e.target;
		cy.elements()
			.difference(ele.outgoers()
				.union(ele.incomers()))
			.not(ele)
			.addClass('semitransp');
		ele.addClass('highlight')
			.outgoers()
			.union(ele.incomers())
			.addClass('highlight');
	});
	cy.on('mouseout', 'node', function(e) {
		var ele = e.target;
		cy.elements()
			.removeClass('semitransp');
		ele.removeClass('highlight')
			.outgoers()
			.union(ele.incomers())
			.removeClass('highlight');
	});

 } );
