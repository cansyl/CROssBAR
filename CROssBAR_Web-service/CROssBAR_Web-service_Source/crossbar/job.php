<!doctype html>
<html lang="en">
<head>
	<?php include('metas.php'); ?>
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/jquery-ui.css">
	<link rel="stylesheet" href="css/custom.css">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
	<link rel="stylesheet" href="css/jquery-ui.css">
	<link rel="stylesheet" href="css/custom.css">

	<?php include('js_files.php'); ?>

</head>

<body>

<?php include('navbar.php'); ?>

<!-- if something went wrong... -->
<div id="error_msg" title="Search Error!"></div>
<?php
	if(isset($_GET['id']))
		if(file_exists('datas/'.$_GET['id'].'.json'))
			$file_name = $_GET['id'];
?>
<main role="main" class="container-fluid">
  <div class="row">
    <aside class="col-md-3">
		<div class="p-4 mb-1 mt-1 mr-0 bg-light rounded">
			<div id="network_manipulation">
			<?php include('network_manipulation_form.php'); ?>
			</div>
		</div>
	</aside>

	<!--<div class="col-md-9 offset-md-3 position-fixed" id="network"></div>-->
	<div class="row col-md-9 p-0 sticky-top" id="network"></div>

  </div><!-- /.row -->
</main><!-- /.container -->

	<script src="js/custom.js"></script>
	
	<script>

	function apply_high(name, file){
		$.ajax( {
		  url: "take_id_of_node.php",
		  data: {
			name: name,
			file: file
		  },
		  success: function( data ) {
			var ele = cy.$('node[id = "'+data+'"]');
			/*
			console.log(ele.outgoers());
			console.log(ele.incomers());
			*/
			/*
			var aa = ele.outgoers().union(ele.incomers());
			console.log(aa);
			*/
			var degree = 
			(ele.outgoers()
				.union(ele.incomers())
				.length) / 2;
			degree = Math.floor(degree);

			$.ajax({
				type:"POST",
				url:'selected_node.php',
				data: {node:ele.data(), degree:degree},
				success: function(result){
					if($('#selected_nodes').hasClass('d-none')){
						$('#selected_nodes').append('<h5 class="row alert alert-secondary p-1 pb-2 mb-0 border-bottom-0 border-secondary"><b>Selected Nodes</b></h5>');
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
		  }
		} );
	}

		data = fetch('datas/'+<?=$file_name?>+'.json', {mode: 'no-cors'}).then(function(res){return res.json()}).then(function(data){
			return data;
		});

		my_style = fetch('css/css.json', {mode: 'no-cors'}).then(function(res) { return res.json()}).then(function(style){
			return style;
		});

		data.then(function(result) {
			result.search.forEach((s) => {
				for (var p in s) {
					//console.log(s);
					if( p == 'Disease' ||  p == 'KEGG Disease' || p == 'Drug' || p == 'KEGG Pathway' || p == 'Pathway' || p == 'HPO'){
						for (var x in s[p]) {
							//console.log(s[p][x]);
							apply_high(s[p][x], <?=$file_name?>);
							$('#search_parameters').append( '<li class="list-group-item">'+p+': '+s[p][x]+'</li>' );
						}
					}else{
						$('#search_parameters').append( '<li class="list-group-item">'+p+': '+s[p]+'</li>' );
						if(p != 'Protein')
							apply_high(s[p], <?=$file_name?>);
					}
				}
			});
			$('#search_parameters').append( '<li class="list-group-item">Number of Nodes: '+result.options.num_of_nodes+'</li>' );
			var fn_stat = 'no';
			if(result.options.fn)
				fn_stat = 'yes';
			$('#search_parameters').append( '<li class="list-group-item">Include interacting proteins: '+fn_stat+'</li>' );
			var rw = 'no';
			if(result.options.reviewed_filter)
				rw = 'yes';
			$('#search_parameters').append( '<li class="list-group-item">Only reviewed genes/proteins: '+rw+'</li>' );
			separator = ',';
			$('#search_parameters').append( '<li class="list-group-item">Included tax Id(s): '+result.options.tax_ids.join(separator)+'</li>' );
		
		});

		var cy = window.cy = cytoscape({
			container: $('#network'),
			style: my_style,
			elements: data,
			layout: {
				name: 'CrossBarLayout',
				fit: 'viewport',
				orderOfNodeTypes: [1,2,3,4,5,6,7]
			}
		});
		/*
		data.then(function(result) {
			console.log(result.nodes);
		});
		*/
	</script>
	<script src="js/add_drop.js"></script>
</body>

</html>
