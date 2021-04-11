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

	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

	<script src="js/cytoscape.min.js"></script>
	<script src="covid19/simplified_autocomplete.js"></script>

</head>

<body>

<?php include('navbar.php'); ?>

<main role="main" class="container-fluid">
  <div class="row">
    <aside class="col-md-3">
		<div class="p-4 mb-1 mt-1 mr-0 bg-light rounded">
			<div id="network_manipulation">
			<?php include('covid19/simplified_network_manipulation_form.php'); ?>
			</div>
		</div>
	</aside>

	<div class="row col-md-9 p-0 position-fixed-desktop" id="network"></div>

  </div><!-- /.row -->
</main><!-- /.container -->

	<script src="covid19/custom.js"></script>
	<script>
		var node_types = [];
		data = fetch('covid19/simplified_covid19_network_data.json', {mode: 'no-cors'}).then(function(res){return res.json()}).then(function(data){
			return data;
		});

		my_style = fetch('covid19/simplified_covid19_style.json', {mode: 'no-cors'}).then(function(res) { return res.json()}).then(function(style){
			return style;
		});

		var cy = window.cy = cytoscape({
			container: $('#network'),
			style: my_style,
			elements: data,
			layout: {
				name: 'preset',
				fit: 'viewport'
			}
		});

	</script>
	<script src="covid19/add_drop.js"></script>
</body>

</html>
