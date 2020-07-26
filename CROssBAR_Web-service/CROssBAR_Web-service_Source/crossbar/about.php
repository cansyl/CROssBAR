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
					<a class="nav-link active" href="#crossbar">CROssBAR Knowledge Graphs</a>
					<a class="nav-link" href="#example">An Example CROssBAR Knowledge Graph</a>
					<a class="nav-link" href="#workflow">Work-flow of the CROssBAR Knowledge Graph Construction Process</a>
					<a class="nav-link" href="#api">Schematic representation of the CROssBAR noSQL database</a>
					<!--<a class="nav-link" href="#howto">How to use the CROssBAR Web-service?</a>-->
				</nav>
			</div>
		</aside>

		<div class="col-md-9 mt-3">
			<div class="row" id="crossbar">
				<h2><u><b>CROssBAR Knowledge Graphs</b></u></h2>
				<p class="mt-2 custom_paragraph">
					The term knowledge graph defines a specialized data representation approach, in which a
					collection of entities are linked to each other in a semantic context. In CROssBAR knowledge
					graphs, biological entities/terms are represented as vertices/nodes. Distinct types of nodes are
					defined for:
				</p>
				<p class="col-12">
					<ul class="custom_paragraph">
					  <li>biomolecules (i.e., genes and proteins),</li>
					  <li>biological mechanisms (i.e., processes/pathways),</li>
					  <li>pathologies (i.e., diseases an phenotypes), and</li>
					  <li>molecules used for treatment (i.e., drugs and drug candidate compounds).</li>
					</ul>
				</p>
				<p class="row col-12 custom_paragraph">
					Relations between different types of biological entities are 
					expressed by the edges of the graph.
					
					Types of edges vary according to the defined relationships. 
					
					The edge labels for a relation between:
				</p>
				<p class="col-12">
				<ul class="custom_paragraph">
				  <li class="">two proteins: "interacts_with",</li>
				  <li class="">a gene/protein and a disease: "raleted_to",</li>
				  <li class="">a drug/compound and a protein: "targets",</li>
				  <li class="">a gene/protein and a pathway: "involved_in",</li>
				  <li class="">a gene/protein and a phenotype term:"associated_with",</li>
				  <li class="">a drug and a disease: "indicates",</li>
				  <li class="">a disease and a pathway: "modulates", and</li>
				  <li class="">a disease and a phenotype term: "associated_with".</li>
				</ul>
				</p>				
			</div>
			
			<div class="row" id="example">
				<!--<center><h3>An Example CROssBAR Knowledge Graph</h3></center>-->
				<p class="row col-12 mt-3">
				<figure class="figure col-6">
				  <figcaption class="figure-caption">
					<center><h4><b>An Example CROssBAR Knowledge Graph</b></h4></center>
				  </figcaption>
				  <a href="images/example.jpg" target=_blank>
				  <img src="images/example.jpg" class="figure-img img-fluid rounded" alt="An Example CROssBAR Knowledge Graph"/>
				  </a>
				</figure>
				</p>

				<p class="row col-12 mt-1">
				<hr class="exppage"/>
				</p>
				
				<p class="row col-12 mt-3 custom_paragraph">
				CROssBAR's user-query specific biomedical knowledge graphs are constructed on-the-fly, in real-
				time. The user query may include one or more genes/proteins, disease/phenotype terms,

				pathways/biological processes and/or drugs/compounds. The full-scale version of the knowledge
				graph construction pipeline is displayed in the diagram below.
				</p>
				<p class="row col-12 custom_paragraph">
				During the construction of a knowledge graph, first, the user queried biological term’s connected
				gene/protein entries (i.e., core genes/proteins) are obtained, such as the member genes/proteins
				of the queried signalling pathway. After that, neighbouring/interacting genes/proteins (i.e., first
				neighbours) are added to the graph. This is followed by the addition of other biological entity
				types by querying the CROssBAR database with the total gene/protein list at hand (both core and
				neighbouring), to obtain the disease terms, phenotypic terms, drugs, compounds and additional
				biological processes/pathways related to these genes/proteins.
				</p>
				
				<p class="row col-12 custom_paragraph">
				At each step of the process, a hypergeometric test is applied to determine the biomedical terms
				that are overrepresented against the gene/protein list at hand, and to filter out the terms with low
				relevance to the graph. If the user starts a heterogenous search that contains multiple terms from
				different entity types, both core and neighbouring genes/proteins are independently collected for

				An Example CROssBAR Knowledge Graph

				each non-protein query term, and the entity collection process is continued using the union of
				these genes/proteins. This approach enables the exploration of direct and indirect relations
				between the queried terms.
				</p>
				
			</div>			
			<div class="row" id="workflow">

				<p class="row col-12 mt-3">
					<figure class="figure col-6">
					  <figcaption class="figure-caption">
						<center><h4><b>Work-flow of the CROssBAR Knowledge Graph Construction Process</b></h4></center>
					  </figcaption>
					  <a href="images/workflow.jpg" target=_blank>
					  <img src="images/workflow.jpg" class="figure-img img-fluid rounded" alt="Work-flow of the CROssBAR Knowledge Graph Construction Process"/>
					  </a>
					</figure>
				</p>

				<p class="row col-12 mt-1">
				<hr class="exppage"/>
				</p>
				
				<p class="row col-12 mt-3 custom_paragraph">

The data source for the CROssBAR knowledge graphs is the CROssBAR NoSQL database, which is
housed at EMBL-EBI servers and communicated via a public RESTful API service at
<a href="https://www.ebi.ac.uk/Tools/crossbar/swagger-ui.html" target=_blank>https://www.ebi.ac.uk/Tools/crossbar/swagger-ui.html</a>. CROssBAR database comprises carefully
selected features from various biomedical data sources namely UniProt, IntAct, InterPro,
DrugBank, ChEMBL, PubChem, Reactome, KEGG, OMIM, Orphanet, Experimental Factor Ontology
(EFO) and Human Phenotype Ontology (HPO), in MongoDB collections. CROssBAR database
schema is provided below.

				</p>
			</div>			  
			<div class="row" id="api">
			<p class="row col-12 mt-3" id="">
				<figure class="figure col-6">
				  <figcaption class="figure-caption">
					<center><h4><b>Schematic representation of the CROssBAR noSQL database</b></h4></center>
				  </figcaption>
				  <a href="images/schematic.png" target=_blank>
				  <img src="images/schematic.png" class="figure-img img-fluid rounded" alt="Schematic representation of the CROssBAR noSQL database"/>
				  </a>
				</figure>
			</p>

		</div>
		</div>
	</div><!-- /.row -->
</main><!-- /.container -->
	
</body>

</html>