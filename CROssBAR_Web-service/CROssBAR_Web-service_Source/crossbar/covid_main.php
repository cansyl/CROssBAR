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

</head>

<body>
<?php include('navbar.php'); ?>

<main role="main" class="container-fluid">

	<div class="row">
		<aside class="col-md-3">
			<div class="p-4 mb-1 mt-1 bg-light rounded sticky-top">
				<nav class="nav flex-column">
					<a class="nav-link active" href="covid-19.php">Large-scale COVID-19 Knowledge Graph</a>
					<a class="nav-link active" href="covid-19_simplified.php">Simplified COVID-19 Knowledge Graph</a>
					<!--<a class="nav-link" href="#howto">How to use the CROssBAR Web-service?</a>-->
				</nav>
			</div>
		</aside>

	<div class="col-md-9 mt-3">

	<div class="row pl-2 pr-5 pt-0">

		<h2 class="row col-12"><u><b>CROssBAR Use-case: COVID-19 Knowledge Graphs</b></u></h2>
		<p class="mt-1 custom_paragraph">
			As a use case of the CROssBAR system, we present the SARS-CoV-2 infection, a.k.a. COVID-19
			CROssBAR knowledge graph. We have constructed 2 different versions of the COVID-19 knowledge
			graph, <i>(i)</i> a large-scale version including nearly the entirety of the related information on different
			CROssBAR-integrated data sources, which is ideal for further network or machine learning based
			analysis, and <i>(ii)</i> a small-scale version distilled to include only the most relevant genes/proteins as
			provided in UniProt-COVID-19 portal (<a href="https://covid-19.uniprot.org" target=_blank>https://covid-19.uniprot.org</a>), which is ideal for fast
			interpretation.
		</p>

		<p class="mt-1 custom_paragraph">
			The finalized large-scale COVID-19 KG includes 987 nodes (i.e., genes/proteins, drugs/compounds,
			pathways, diseases/phenotypes) and 3639 edges (i.e., various types of relations). The simplified
			COVID-19 KG includes a total of 178 nodes and 298 edges. 
			The details of statistics can be found in Table S.3 of the project paper.
			Since most of the COVID-19 related data has still not been
			integrated into the regular pipelines of the source biological databases, the entirety of the data
			could not be pulled to the CROssBAR database automatically, as of July 2020. As a result, we
			manually obtained the data from these resources. We applied the same knowledge graph
			methodology incorporated in CROssBAR to construct the networks and saved the pre-constructed
			graphs, which are accessible through the links below:
		</p>
		
		<p class="mt-1 custom_paragraph">
			For more information about the COVID-19 knowledge graphs, please refer to our project paper or
			visit the CROssBAR project GitHub repository at:
			<a href="https://github.com/cansyl/crossbar" target=_blank>https://github.com/cansyl/CROssBAR</a>
		</p>
		
	</div>

	<div class="row">
		<div class="p-5 col-md-6 col-sm-12 bg-light border">
			<figure class="figure">
			  <figcaption class="figure-caption border-bottom">
				<center><a href="covid-19.php"><h4><i>(i)</i> Large-scale COVID-19 Knowledge Graph</h4></a></center>
			  </figcaption>
			  <a href="covid-19.php">
			  <img src="images/covid-19.png" class="figure-img img-fluid rounded" alt="Full-scale COVID-19 Knowledge Graph"/>
			  </a>
			</figure>
		</div>
		<div class="p-5 col-md-6 col-sm-12 bg-light border">
			<figure class="figure">
			  <figcaption class="figure-caption border-bottom">
				<center><a href="covid-19_simplified.php"><h4><i>(ii)</i> Simplified COVID-19 Knowledge Graph</h4></a></center>
			  </figcaption>
			  <a href="covid-19_simplified.php">
			  <img src="images/covid-19_simplified.png" class="figure-img img-fluid rounded" alt="Simplified COVID-19 Knowledge Graph"/>
			  </a>
			</figure>
		</div>
	</div><!-- /.row -->
	
	</div>
	</div><!-- /.row -->
</main><!-- /.container -->
	
</body>

</html>