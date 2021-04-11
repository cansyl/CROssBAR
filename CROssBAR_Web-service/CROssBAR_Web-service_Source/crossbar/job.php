<?php

	if(isset($_GET['bulk'])){
		if(!file_exists('bulks/'.$_GET['bulk']))
			header("Location: index.php");
		else
			$directory_of_job = 'bulks/'.$_GET['bulk'].'/';
	}else
		$directory_of_job = 'data/';

	if(isset($_GET['id'])){
		if(file_exists($directory_of_job.$_GET['id'].'.json'))
			$file_name = $_GET['id'];
		else
			header("Location: index.php");
	}else
		header("Location: index.php");

?>
<!doctype html>
<html lang="en">
<head>
	<?php include('metas.php'); ?>
	<link rel="stylesheet" href="css/bootstrap.min.css">
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
		<div class="p-4 mb-1 mt-1 mr-0 bg-light rounded">
			<div id="network_manipulation">
			<?php include('network_manipulation_form.php'); ?>
			</div>
		</div>
	</aside>
	<div class="row col-md-9 p-0 sticky-top" id="network">
		<div id="canvas_bg" class="col-md-2">
			<img src="images/CROssBAR_KG_Legend.png" class="img-fluid" id="canvas_bg_item"/>
		</div>
	</div>
  </div><!-- /.row -->
</main><!-- /.container -->

<script src="js/graph_events.js"></script>
<script id="graph_starter" data-attr="<?=$directory_of_job.$file_name?>" src="js/draw_network.js"></script>
<script src="js/search_in_graph.js"></script>
<script src="js/add_drop.js"></script>	
<script src="js/network_customization.js"></script>	
<script src="js/page_ready.js"></script>	

</body>
</html>
