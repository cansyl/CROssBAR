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
					<a class="nav-link" href="#howto">How to use the CROssBAR Web-service?</a>
				</nav>
				<nav class="nav flex-column">
					<a class="nav-link" href="#example">An Example CROssBAR Query</a>
				</nav>
				<nav class="nav flex-column">
					<a class="nav-link" href="#options">Visualization Options</a>
				</nav>
			</div>
		</aside>

		<div class="col-md-9 mt-3">
			<div class="row" id="howto">
				<h2><u><b>How to use the CROssBAR Web-service?</b></u></h2>	
				<p class="row col-12 custom_paragraph">
				CROssBAR web-service is especially designed to provide 
				an easy to use interface to the researchers
				from different fields of molecular life sciences. 
				The horizontal menu at the top has links to provide
				information about the service, knowledge graphs and 
				about the project in general. The menu also
				includes example use-cases of COVID-19 knowledge graphs. 
				Search button at the left-most
				side of the menu displays the main page for constructing 
				user queries.
				</p>
				<p class="row col-12">
				<img src="images/menu.png" class="img-fluid" width="" alt=""/>
				</p>
				<p class="row col-12 mt-1">
				<hr class="exppagefull"/>
				</p>
			</div>

			<div class="row">
				<p class="row col-12 custom_paragraph mt-3">
				At the main pane, there is a vertical query menu on the left side of the page. On the right side,
				there is a blank area to house the knowledge graph once it is constructed.
				</p>
				<p class="row col-12">
				<img src="images/search_form.png" class="img-fluid" width="" alt=""/>
				</p>
				
				<p class="row col-12 mt-0">
				<hr class="exppagefull"/>
				</p>
				
			</div>
			
			<div class="row">
				<p class="row col-12 custom_paragraph">
				The user may construct complex queries by simply entering the biological entities she/he is
				interested in by typing the names/ids in their respective boxes, located inside the vertical query
				menu.
				</p>
				<p class="row col-12">
				<img src="images/search_form_2.png" class="img-fluid" width="" alt=""/>
				</p>
				<p class="row col-12 mt-0">
				<hr class="exppagefull"/>
				</p>		
			</div>
			
			<div class="row">
				<p class="row col-12 custom_paragraph mt-3">
				There are 5 boxes reserved for writing the names of diseases, 
				drugs/compounds, pathways,
				genes/proteins and phenotype terms, respectively. 
				After typing a few letters, the system suggests
				terms that exist in the database, 
				which match the typed letters. 
				The user should select a term
				from this list. 
				Following the selection of a term from the provided list, 
				additional terms can be
				appended to the same box by continuing to type another name. 
				There are no automated
				suggestions for small molecule compounds as they are queried 
				via ChEMBL ids, that is in the
				format of "CHEMBL123456", where the numeric part is the 
				actual identifier.
				</p>
				<p class="row col-12">
				<img src="images/search_form_3.png" class="img-fluid" width="356" alt=""/>
				</p>
				<p class="row col-12 mt-0">
				<hr class="exppagefull"/>
				</p>
			</div>

			<div class="row">
				<p class="row col-12 custom_paragraph mt-3">
					Taxonomic id filter box is located below the term search boxes. The default value for the
					taxonomic filter is 9606 (human), since the main focus of CROssBAR is biomedicine. This filter is
					mainly used to include the genes/proteins of non-human organisms in the query, together with
					their respective relations. It is possible use the taxonomic filter to include genes/proteins from a
					few additional organisms namely, Rattus norvegicus (Rat) [10116], Mus musculus (Mouse)
					[10090], Sus scrofa (Pig) [9823], Bos taurus (Bovine) [9913], Oryctolagus cuniculus (Rabbit) [9986],
					Saccharomyces cerevisiae (Baker's yeast) [559292], Mycobacterium tuberculosis [83332] and

					Escherichia coli - strain K12 [83333]. It is possible to include multiple organisms in the query by
					combining their tax ids in the respective box.
				</p>
				<p class="row col-12">
				<img src="images/search_form_4.png" class="img-fluid" width="356" alt=""/>
				</p>
				<p class="row col-12 mt-0">
				<hr class="exppagefull"/>
				</p>
			</div>

			<div class="row">
				<p class="row col-12 custom_paragraph mt-3">
				The box located below is used to enter the number of nodes to be included in the network
				following the enrichment/overrepresentation analysis. For each biomedical entity type (e.g.,
				disease, phenotype, drug, compound, pathway, gene/protein) the terms that are related to
				genes/proteins in the knowledge graph are ranked starting from the most overrepresented term.
				The default value for the number of nodes is 10, which means that the top 10 overrepresented
				pathway, disease, phenotype, drug, compound and gene/protein entries will be included in the
				knowledge graph.
				</p>
				<p class="row col-12">
				<img src="images/search_form_5.png" class="img-fluid" width="356" alt=""/>
				</p>
				<p class="row col-12 mt-0">
				<hr class="exppagefull"/>
				</p>
			</div>

			<div class="row">
				<p class="row col-12 custom_paragraph mt-3">
				Following that, there are two buttons, first one to include or exclude 
				the proteins that are directly
				interacting with the collected core proteins, 
				into the network. This is included in the default
				version. The second one is used to filter out the unreviewed 
				gene/protein entries (obtained from
				the UniProtKB/TrEMBL database) from the knowledge graphs to 
				avoid redundancy. The default
				selection is only including the reviewed gene/protein entries. 
				The user can include unreviewed
				UniProtKB/TrEMBL database entries to the search by unchecking 
				this box, however, for the cases
				where the number of reviewed core genes/proteins exceed 50, 
				unreviewed entries are discarded
				since it increase the number of core genes/proteins to an 
				extent (e.g., thousands) where it is
				nearly impossible to continue collecting related non-protein 
				terms and to apply enrichment
				analysis on them. If the user is interested in one or more 
				unreviewed protein entries, it is advised
				to initiate a query by typing the UniProt accessions of 
				these unreviewed entries in the
				gene/protein search box and by unchecking the only reviewed 
				gene/protein entries box.
				The last button at the bottom is used to initiate the 
				automated knowledge graph construction
				process, following the entering of desired input terms and 
				parameters.
				</p>

				<p class="row col-12 custom_paragraph">
				The last button at the bottom is used to initiate the automated knowledge graph 
				construction process, following the entering of desired input terms and parameters.
				</p>
			
				<p class="row col-12">
				<img src="images/search_form_6.png" class="img-fluid" width="356" alt=""/>
				</p>
				<p class="row col-12 mt-0">
				<hr class="exppagefull"/>
				</p>
			</div>

			<div class="row mt-3" id="example">
				<h4><u><b>An Example CROssBAR Query:</b></u></h4>
				<p class="row col-12 custom_paragraph mt-2">
				In this example, we would like to observe the relations 
				between a disease, gastric cancer and a drug, 
				trifluoperazine, which is an approved antipsychotic 
				agent mainly used in the treatment of schizophrenia. 
				Trifluoperazine has no in vitro, in vivo or 
				clinical studies concerning the treatment of gastric cancer, 
				although there are studies on other types of cancer such as 
				colorectal [1], pancreatic [2], and lung [3], in the literature. 
				There is also a study showing the inverse association 
				between antipsychotic use and the risk of gastric cancer [4]. 
				Thus, this is a convenient scenario for observing unknown 
				relationship between 2 biological entities, 
				gastric cancer and trifluoperazine. 
				The search parameters are shown below.
				</p>
				<p class="row col-12">
				<img src="images/search_form_7.png" class="img-fluid" width="356" alt=""/>
				</p>

				<p class="row col-12 custom_paragraph mt-2">
					The knowledge-graph constructed with this query is given below.				
				</p>
				<div class="row">
					<p class="row col-12 mt-1">
						<figure class="figure col-9">
						  <figcaption class="figure-caption">
							<center><h4><b></b></h4></center>
						  </figcaption>
						  <a href="images/example_query.png" target=_blank>
						  <img src="images/example_query_small.png" class="figure-img img-fluid rounded" alt="Example knowledge-graph."/>
						  </a>
						</figure>
					</p>
				</div>
				
				<p class="row col-12 custom_paragraph mt-2">
				Trifluoperazine shows its antipsychotic effect by the blockage of dopamine D2 receptor. This
				relation also appears in the graph, where trifluoperazine binds to DRD2 gene/protein node and it
				is associated with the dopaminergic synapse pathway. In the KG, trifluoperazine also has other
				approved targets such as CALM1, ADRA1A and TNNC1 proteins (approved drug-target interaction

				edges are coloured with green), and these proteins are associated with calcium signalling
				pathway. Moreover, DRD2 and CALM1 are associated with rap1 signalling pathway, as well. Both
				calcium and rap1 signalling pathways have other gene/protein associations such as ERBB2, KRAS,
				and CDH1, which are associated with the gastric cancer disease. Therefore, trifluoperazine can be
				explored further in terms of its potential to become a repurposed agent for the treatment of
				gastric cancer, which may show its activity via calcium and rap1 signalling pathways.
				</p>

				<div class="row">
					<p class="row col-12 mt-1">
						<figure class="figure col-9">
						  <a href="images/example_query_zoom.png" target=_blank>
						  <img src="images/example_query_zoom_small.png" class="figure-img img-fluid rounded" alt="Example knowledge-graph."/>
						  </a>
						</figure>
					</p>
				</div>

				<p class="row col-12 custom_paragraph mt-2">
				KRAS and ERBB2 proteins are also related to other cancer disease nodes such as the pancreatic
				cancer, cervical cancer, endometrial cancer and cholangiocarcinoma, and associated with HPO
				terms such as the stomach cancer, which means that trifluoperazine may also have a potential
				against these cancer types, worthy of further exploration. Other antipsychotic or anxiolytic agents
				such as risperidone, haloperidol, perphenazine, buspirone, droperidol, and prochlorperazine are
				enriched in the network as well, which bind to DRD2, CALM1 and/or ADRA1A (with green edges).
				These drugs may also become alternative repurposing drugs for gastric cancer treatment or other
				cancers involved in the KG.
				CALM1, ADRA1A and TNCC1 genes are associated with adrenergic signalling in cardiomyocytes
				pathway, too. This relation may explain the adverse effects of antipsychotic drugs regarding
				cardiovascular diseases, in long term usage [5]. In addition to the above mentioned approved
				drug-target interactions, the graph also includes enriched drugs and compounds having
				experimentally measured (with blue edges) or computationally predicted (with red edges)
				bioactivities against the targets DRD2, ADRA1A, EBP, and SIGMAR1. Furthermore, there are a few
				phenotypes (HPO terms), such as the abnormal urine carbohydrate level and the congenital
				hypertrophy of retinal pigment epithelium, which are associated with gastric cancer disease node
				and/or gastric cancer related genes. These phenotypic implications could be helpful for disease
				diagnosis.

				<p class="row col-12 custom_paragraph mt-2">
					<u>References</u>
					<ol class="custom_paragraph" id="references">
					  <li>Xia Y, Jia C, Xue Q, et al. Antipsychotic drug trifluoperazine suppresses colorectal cancer by inducing G0/G1 arrest and apoptosis. Front. Pharmacol. 2019; 10:1029</li>
					  <li>Huang C, Lan W, Fraunhoffer N, et al. Dissecting the Anticancer Mechanism of Trifluoperazine on Pancreatic Ductal Adenocarcinoma. Cancers (Basel). 2019; 11:1869</li>
					  <li>Yeh CT, Wu ATH, Chang PMH, et al. Trifluoperazine, an antipsychotic agent, inhibits cancer stem cell growth and overcomes drug resistance of lung cancer. Am. J. Respir. Crit. Care Med. 2012; 186:1180–1188</li>
					  <li>Hsieh Y, Chan H, Lin C, et al. Antipsychotic use is inversely associated with gastric cancer risk: A nationwide population-based nested case-control study. Cancer Med. 2019; 8:cam4.2329</li>
					  <li>Rotella F, Cassioli E, Calderani E, et al. Long-term metabolic and cardiovascular effects of antipsychotic drugs. A meta-analysis of randomized controlled trials. Eur. Neuropsychopharmacol. 2020; 32:56–65</li>
					</ol>
				</p>
				<p class="row col-12 mt-0">
				<hr class="exppagefull"/>
				</p>
			</div>

			<div class="row mt-3" id="options">
				<h4><u><b>Visualization Options</b></u></h4>
				<p class="row col-12 custom_paragraph mt-2">
					Considering the visual customization of the constructed graph, it is possible to apply different
					layout options from "Layout selection" part under "Graph Customization" menu, on the left side of
					the page. The user has 5 different layout options, which are the CROssBAR layout, circle, grid,
					cose, and the concentric layout.
				</p>
				<p class="row col-12">
				<img src="images/visualization_1.png" class="img-fluid" width="356"/>
				</p>
				<p class="row col-12 mt-0">
				<hr class="exppagefull"/>
				</p>

				<p class="row col-12 custom_paragraph mt-2">			
				It is also possible to arrange node sizes in the graph by moving the blue cursor in the "Size of
				Nodes" section under the same menu.
				</p>
				<p class="row col-12">
				<img src="images/visualization_2.png" class="img-fluid" width="356"/>
				</p>
				<p class="row col-12 mt-0">
				<hr class="exppagefull"/>
				</p>
				
				<p class="row col-12 custom_paragraph mt-2">			
				If the selected layout is the "CROssBAR Layout", the graph can be displayed either in 4 or 7-
				circular layers by clicking corresponding buttons. Also, the order of layers can be changed (the
				ranking is from inside to out) by dragging node types in "# & order of layers" part of the menu.
				After clicking the "Apply" button, the changes are displayed on the graph.
				</p>
				<p class="row col-12">
				<img src="images/visualization_3.png" class="img-fluid" width=""/>
				</p>
				<p class="row col-12 mt-0">
				<hr class="exppagefull"/>
				</p>

				<p class="row col-12 custom_paragraph mt-2">
				If the user clicks any node on the graph, "Selected Nodes" menu will appear at the top of the
				"Graph Customization" menu and it brings specific information for these nodes, including the
				component type, name, id/link, and the node degree.
				</p>
				<p class="row col-12">
				<img src="images/visualization_4.png" class="img-fluid" width=""/>
				</p>

				<p class="row col-12 mt-0">
				<hr class="exppagefull"/>
				</p>

				<p class="row col-12 custom_paragraph mt-2">
				The user can click on the "Component List" button under the "Add/remove components" part of
				the "Graph Customization" menu, which will bring a list of node types in the graph including
				diseases, HPO terms, pathways, drugs and compounds. Then, the user can hide the nodes from
				the graph by clicking unwanted node types on the list.
				</p>
				<p class="row col-12">
				<img src="images/visualization_5.png" class="img-fluid" width=""/>
				</p>

				<p class="row col-12 mt-0">
				<hr class="exppagefull"/>
				</p>
				

				<p class="row col-12 custom_paragraph mt-2">
				The job id of the related query is available on "Query Parameters" menu, together with search
				terms and the selected parameters. Each job is saved on the web-server for a limited amount of
				time. Therefore, it is possible to access the constructed graph later by entering corresponding job
				id into "Enter Job ID" box on the horizontal menu at the top of the page.
				</p>
				<p class="row col-12">
				<img src="images/visualization_6.png" class="img-fluid" width=""/>
				</p>
				<p class="row col-12">
				<img src="images/visualization_7.png" class="img-fluid" width=""/>
				</p>

				<p class="row col-12 mt-0">
				<hr class="exppagefull"/>
				</p>

				<p class="row col-12 custom_paragraph mt-2">
				There are different download options to save the graph output. The user can download the
				constructed KG as a protein-centric table in "csv" file format, which includes proteins in the first
				column and their interacting biological entities in the remaining columns. The KG can be also
				downloaded as a network in json format. To see the construction steps of the KG and the
				enrichment scores of the nodes in the graph, a downloadable query report is also available. As the
				final download option, the user can export the KG as an image in "png" format, as well. The image
				can either be exported in terms of the part visible on the screen, or as the whole network, with
				normal and high-resolution download options.
				</p>
				<p class="row col-12">
				<img src="images/visualization_8.png" class="img-fluid" width=""/>
				</p>

				<p class="row col-12 mt-0">
				<hr class="exppagefull"/>
				</p>

				<p class="row col-12 custom_paragraph mt-2">
				At the bottom of the vertical menu on the left side of the page, there is a final button named “New
				Knowledge Graph Query” to allow a new search.
				</p>
				<p class="row col-12">
				<img src="images/visualization_9.png" class="img-fluid" width=""/>
				</p>
				
			</div>
		</div>
	</div><!-- /.row -->
</main><!-- /.container -->
	
</body>

</html>