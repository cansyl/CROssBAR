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

<main role="main" class="container-fluid">
	<div class="row">
		<aside class="col-md-3">
			<div class="p-4 mb-1 mt-1 bg-light rounded sticky-top">
				<nav class="nav flex-column">
					<a class="nav-link active" href="#crossbar">CROssBAR Project</a>
					<a class="nav-link" href="#schematic_representation">Schematic Representation of the CROssBAR project</a>
					<a class="nav-link" href="#team">Team</a>
				</nav>
			</div>
		</aside>

		<div class="col-md-9 mt-3">
			<div class="row" id="crossbar">
				<h2 class="row col-12"><u><b>CROssBAR Project</b></u></h2>
				<p class="mt-2 custom_paragraph">
				<b>CROssBAR: C</b>omprehensive <b>R</b>esource <b>o</b>f <b>B</b>iomedic<b>a</b>l 
				<b>R</b>elations with Deep Learning 
				Applications and Knowledge Graph Representations
				</p>
				<p class="mt-2 custom_paragraph">
				CROssBAR is a comprehensive system that integrates large-scale biomedical data from various
				resources and store it in a new NoSQL database, enrich this data with deep learning based
				prediction of relations between numerous biomedical entities, rigorously analyse the enriched
				data to obtain biologically meaningful modules and display them to the user via easy to interpret,
				interactive and heterogenous knowledge graphs within an open access, user-friendly and online
				web-service at
				<a href="https://crossbar.kansil.org" target=_blank>https://crossbar.kansil.org</a>.
				</p>
				<p class="row col-12 custom_paragraph">
				5 main research objectives were fulfilled to construct the CROssBAR resource:				</p>
				<p class="col-12">
				<ol class="custom_paragraph">
				  <li>
				  <u>Biomedical data integration:</u> 
CROssBAR database is constructed by collecting relational
data from various biomedical data resources UniProt, IntAct, DrugBank/ChEMBL/PubChem,
Reactome/KEGG, OMIM/Orphanet/EFO/HPO by persisting specific data attributes with the
implementation of logic rules, in MongoDB collections.
				  </li>
				  <li>
				  <u>Deep learning-based predictive models:</u>  
the main purpose here was to enrich the
integrated biomedical data by identifying the unknown interactions between drugs / drug
candidate compounds and target proteins. We trained our systems using carefully filtered
data in the CROssBAR database, and ran our trained-models on large-scale compound and
protein spaces to obtain comprehensive bio-interaction predictions.
				  </li>
				  <li>
				  <u>Biomedical knowledge graphs:</u>  
Different biological components; drugs/compounds,
genes/proteins, pathways, phenotypes/diseases are represented as nodes, and their
known and predicted relationships are annotated as edges. These intensely-processed
heterogeneous biological networks will be utilized to aid biomedical research, especially to
infer mechanisms of diseases in relation to biomolecules, systems and candidate drugs.
				  </li>
				  <li>
				  <u>Open-access web-service:</u>  
The aim here is to make the CROssBAR data available to the
public in an easily interpretable, interactive way. Knowledge graphs are presented visually
on web-browsers as Cytoscape networks. Users can make searches with CROssBAR
components, individually or in combination, to obtain relevant sub-networks, which is
constructed on-the-fly.
				  </li>
				  <li>
				  <u>Experimental validation:</u>  
In vitro cell based wet-lab experiments are conducted based on
the computationally-inferred information, with the purposes of verifying the predictions
and also for gaining biological insight in the framework of health and disease, especially to
make a contribution to the understanding of processes active in certain cancer subtypes.
				  </li>
				</ol>
				</p>				
			</div>
				<p class="mt-2 custom_paragraph">
				For more details about the project please visit our 
				project GitHub repository at: 
				<a href="https://github.com/cansyl/CROssBAR" target=_blank>https://github.com/cansyl/CROssBAR</a> 
				and 
				our project website at: 
				<a href="http://cansyl.metu.edu.tr/crossbar" target=_blank>http://cansyl.metu.edu.tr/crossbar</a>
				</p>
			<div class="row" id="schematic_representation">

				<p class="row col-12 mt-3">
				<figure class="figure col-9">
				  <figcaption class="figure-caption">
					<center><h4><b>Schematic Representation of the CROssBAR project</b></h4></center>
				  </figcaption>
				  <a href="images/schematic_representation.png" target=_blank>
				  <img src="images/schematic_representation.png" class="figure-img img-fluid rounded" alt="Schematic Representation of the CROssBAR project"/>
				  </a>
				</figure>
				</p>

				<p class="row col-12 mt-1">
				<hr class="exppage"/>
				</p>
				
				<p class="row col-12 mt-3 custom_paragraph" id="team">
				<h3><b><u>CROssBAR Team:</b></u></h3>
				<p class="custom_paragraph">
				Comprehensive Resource of Biomedical Relations with Deep Learning and Network
				Representations (CROssBAR) is a joint research project between the Middle East Technical
				University (METU) and the European Bioinformatics Institute (EMBL-EBI) . This project is funded by
				the Scientific and Technological Research Council of Turkey (TUBITAK) and British Council, UK. The
				people working on the project is given below: 
				</p>
				</p>
			</div>

			<p class="row col-12 mt-3">
			<figure class="figure col-9">
			  <figcaption class="figure-caption">
				<center><h4><b>CROssBAR Project Team</b></h4></center>
			  </figcaption>
			  <a href="images/team.png" target=_blank>
			  <img src="images/team.png" class="figure-img img-fluid rounded" alt="CROssBAR Project Team"/>
			  </a>
			</figure>
			</p>
			
		</div>
	</div><!-- /.row -->
</main><!-- /.container -->

<script src="js/custom.js"></script>	
</body>

</html>