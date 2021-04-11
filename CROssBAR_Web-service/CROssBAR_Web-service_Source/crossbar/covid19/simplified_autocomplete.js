$( function() {
	$('#network').height( screen.height - $('#top_menu').height() - 150 );
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
			  url: "covid19/simplified_search_in_network.php",
			  dataType: "json",
			  data: {
				term: request.term
			  },
			  success: function( data ) {
				response( data );
			  }
			} );
        },
        focus: function() {
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          terms.pop();
          this.value = terms.join( '' );
		  apply_high(ui.item.value);
          return false;
        }
      });

	function add_node2side(node_id, result){
		if($('#selected_nodes').hasClass('d-none')){
			$('#selected_nodes').append('<h5 class="row alert alert-secondary p-1 mb-0 border-bottom-0 border-secondary">Selected Nodes</h5>');
			$('#selected_nodes').removeClass('d-none');
			$('#selected_nodes').append(result);
		}else{
			
			var field_id = node_id.replace(":", "_");
			if($("#node_" + field_id).length == 0) {
			  $('#selected_nodes').append(result);
			}
		}
	}

	function apply_high(name){
		$.ajax( {
		  url: "covid19/simplified_take_id_of_node.php",
		  data: {
			name: name
		  },
		  success: function( node_id ) {
			var ele = cy.$('node[id = "'+node_id+'"]');
			var degree = 
			(ele.addClass('highlight')
				.outgoers()
				.union(ele.incomers())
				.length) / 2;

			$.ajax({
				type:"POST",
				url:'covid19/simplified_selected_node.php',
				data: {node:ele.data(), degree:degree},
				success: function(result){
					add_node2side(node_id,result);
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
			$('#selected_edges').html('');
			$('#selected_edges').addClass('d-none');
			// if clicked on the empty area (canvas)
			cy.$('node').removeClass('highlightednode');
			cy.$('node').removeClass('zelected');
			cy.$('edge').forEach((ed) => {
				ed.animate({
					style: {width: "1px","opacity":"0.75","font-size": "0px"}
				});
			});
		}
	});

	cy.on('select', function(e){
		var ele = e.target;

		if(ele.data().source){

			conn_nodes = ele.connectedNodes();
			el1 = conn_nodes[0].data();
			if(conn_nodes.length === 1){
				// self interaction
				el2 = conn_nodes[0].data();
			}else{
				el2 = conn_nodes[1].data();
			}		
			ele.connectedNodes().addClass('highlightednode');
			ele.animate({
				style: {width: "3px","opacity":"1","font-size": "10px"}
			});

			$.ajax({
				type:"POST",
				url:'covid19/selected_edge.php',
				data: {node1:el1,node2:el2,Edge:ele.data()},
				success: function(result){
					if($('#selected_edges').hasClass('d-none')){
						$('#selected_edges').append('<h5 class="row alert alert-secondary p-1 mb-0 border-bottom-0 border-secondary">Selected Edges</h5>');
						$('#selected_edges').removeClass('d-none');
						$('#selected_edges').append(result)
					}else{
						if($('#edge_'+ ele.data().id).length == 0)
							$('#selected_edges').append(result);
					}
				}
			});


		}else{
			
			var degree = 
			(ele.addClass('highlight')
				.outgoers()
				.union(ele.incomers())
				.length) / 2;

			$.ajax({
				type:"POST",
				url:'covid19/simplified_selected_node.php',
				data: {node:ele.data(), degree:degree},
				success: function(result){
					add_node2side(ele.data().name,result);
				}
			});
			
			ele.addClass('zelected');
			ele.connectedEdges().connectedNodes().addClass('highlightednode');
			ele.connectedEdges().animate({
				style: {width: "3px","opacity":"1","font-size": "10px"}
			});
		}
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
