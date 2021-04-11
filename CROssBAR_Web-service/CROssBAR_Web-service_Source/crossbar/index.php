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
<div id="error_msg" title="Search Error!">
<div class="alert alert-danger"><b>Operation terminated.</b> Possible reasons and solutions:</div>
<div id="reasons_of_fail">
  <ul class="row">
    <li class="col-2 m-0"><a href="#tabs-1">1</a></li>
    <li class="col-2 m-0"><a href="#tabs-2">2</a></li>
    <li class="col-2 m-0"><a href="#tabs-3">3</a></li>
    <li class="col-2 m-0"><a href="#tabs-4">4</a></li>
    <li class="col-2 m-0"><a href="#tabs-5">5</a></li>
    <li class="col-2 m-0"><a href="#tabs-6">6</a></li>
  </ul>
  <div id="tabs-1">
    <p><b>1)</b> Query term(s) could not be found in the database, or the query terms have no relations to be used in the graph construction (please choose your query term name from the suggestion list, except or re-check your UniProt protein accession(s) / ChEMBL compound id(s).</p>
  </div>
  <div id="tabs-2">
    <p><b>2)</b> Queried term(s) are not related to the selected organism (the default organism is human, you may include additional organisms to your query using the taxonomic filter).</p>
  </div>
  <div id="tabs-3">
    <p><b>3)</b> Query protein accession(s) is not presented in UniprotKB/Swiss-Prot (you may uncheck the button “Include only reviewed gene/protein entries” and search again).</p>
  </div>
  <div id="tabs-4">
    <p><b>4)</b> It was not possible to construct a graph since query term(s) do not have documented relationships.</p>
  </div>
  <div id="tabs-5">
    <p><b>5)</b> Query term(s) have an elevated number of relationships (especially genes/proteins) as a result query did not return a result (please try to narrow down the search space by querying a more specific term(s) or less number of terms).</p>
  </div>
  <div id="tabs-6">
    <p><b>6)</b> Server is busy (please try again).</p>
  </div>
</div>
</div>

<main role="main" class="container-fluid">
  <div class="row">
    <aside class="col-md-3">
		<div class="p-4 mb-1 mt-1 bg-light rounded">
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

	<script src="js/autocomplete.js"></script>
	<script src="js/graph_const.js"></script>
	<script src="js/search_form.js"></script>

	<?php
		if(isset($_GET['autostart'])){
			$file = fopen("tmps/".$_GET['autostart'].".txt","r");
			?>
		<script>
			manuel_start_search(<?php echo json_encode(fgets($file)); ?>);
		</script>
	<?php
			fclose($file);
		}
	?>

</body>

</html>
