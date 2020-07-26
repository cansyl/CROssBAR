<!doctype html>
<html lang="en">
<head>
	<?php include('metas.php'); ?>
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

<main role="main" class="container-fluid">
  <div class="row">
    <aside class="col-md-3">
		<div class="p-4 mb-1 mt-1 bg-light rounded">
			<div id="network_manipulation" class="d-none">
			<?php include('network_manipulation_form.php'); ?>
			</div>
			<div id="search_form_area">
			<?php include('network_search_form.php'); ?>
			</div>
		</div>
	</aside>

	<div class="col-md-9" id="network">
		<div id="progress_info">
			<div id="progress_status"></div>
			<div class="progress">
			  <div class="progress-bar progress-bar-striped  progress-bar-animated" role="progressbar" style="width: 0%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
			</div>
		</div>
	</div>

  </div><!-- /.row -->

</main><!-- /.container -->

	<script src="js/custom.js"></script>

	<script>
		my_style = fetch('css/css.json', {mode: 'no-cors'}).then(function(res) { return res.json()}).then(function(style){
			return style; 
		});

		var cy = window.cy = cytoscape({
			container: $('#network'),
			style: my_style,
			elements: []
		});

	</script>
	
	
</body>

</html>
