# CROssBAR: Comprehensive Resource of Biomedical Relations with Deep Learning Applications and Knowledge Graph Representations

The purpose of the CROssBAR project is to address the limitations related to data diversity and connectivity in biological data resources, which hamper their real-world applications to biomedical problems. Within CROssBAR, we developed a comprehensive computational resource by linking various biomedical resources, generating relation predictions using machine/deep learning, and developing information rich knowledge graphs that incorporate available and predicted biomedical relationships with the aim of providing aid to biomedical researchers to further understand disease mechanisms and to discover/develop new drugs.

## About the Project

![CROssBAR-Overview](https://user-images.githubusercontent.com/13165170/88054337-d9892280-cb65-11ea-9c88-94639a5801aa.png)

**Sub-projects under CROssBAR:**

**1) Biomedical data integration:** CROssBAR database is constructed by collecting relational data from various biomedical data resources UniProt, IntAct, InterPro, Reactome, Ensembl, DrugBank, ChEMBL, PubChem, KEGG, OMIM, Orphanet, Gene Ontology, Experimental Factor Ontology (EFO) and Human Phenotype Ontology (HPO) by persisting specific data attributes with the implementation of logic rules, in MongoDB collections. Open access CROssBAR-DB can be queried via our public RESTful API, which provides a multi-faceted view of the stored data.

**2) Deep learning-based relation prediction:** the main purpose here was to enrich the integrated biomedical data by identifying the unknown interactions between drugs / drug candidate compounds and target proteins. We re-trained our previously developed systems using carefully filtered and up-to-date data in the CROssBAR database, and ran our models on large-scale compound and protein spaces to obtain comprehensive bio-interaction predictions, including drug predictions for COVID-19.

**3) Biomedical knowledge graphs:** Different biological components; drugs/compounds, genes/proteins, pathways/mechanisms, phenotypes/diseases are represented as nodes, and their known (reported) and computationally predicted relationships are annotated as edges. At each step of process, overrepresentation-based enrichment analyses are applied to construct a graph that is highly relevant to the query term(s). These intensely-processed heterogeneous biological networks is expected to be utilized to aid biomedical research, especially to infer mechanisms of diseases in relation to biomolecules, systems and candidate drugs.

**4) CROssBAR web-service:** Here we developed a service to make the CROssBAR data available to the public in an easily interpretable, interactive way via an online graphical user interface. Knowledge graphs are presented visually on web-browsers as Cytoscape networks. Users can make searches with CROssBAR components by simply typing the names or ids of the query terms individually or in combination, to obtain relevant sub-graphs, constructed on-the-fly.

**5) COVID-19 and other use-cases:** CROssBAR COVID-19 knowledge graphs are constructed with aim of collecting the related data from various biomedical resources, applying filtering operations and presenting it in a coherent and standardized form to the research community. Along with up-to-date information reported in source databases, our COVID-19 KGs also incorporates several new drugs (either by enrichment analysis or predicted by our deep-learning models) that can contribute to the studies on developing novel medications against SARS-CoV-2. We also conducted *in vitro* cell based wet-lab experiments (i.e., gene expression analysis) to compare its results with the computationally-inferred information.
<br>
## CROssBAR Database & API

<img src="https://user-images.githubusercontent.com/13165170/88060719-42c16380-cb6f-11ea-84c4-e7f7163e152d.png" width="600"> 

We constructed the CROssBAR database to integrate vast amounts of biological information from various well-known resources. Data pipelines are developed for the heavy lifting of data from different databases such as UniProt, IntAct, DrugBank, ChEMBL, PubChem, Reactome, KEGG, OMIM, Orphanet and EFO, by persisting specific data attributes with the implementation of logic rules.

The CROssBAR database of attributes is hosted in self-sufficient, easy to access collections in MongoDB and it is available both to end-users and to the CROssBAR webservice through an API at: [CROssBAR-API](https://www.ebi.ac.uk/Tools/crossbar/swagger-ui.html).

Technologies used:
* Java 8,
* Mongo DB v3.4.9,
* Groovy and Spock framework for tests,
* Maven dependency management  

## Deep Learning-based Relation Prediction

<ins>**DEEPScreen:**</ins>

<img src="https://user-images.githubusercontent.com/13165170/88064485-05aba000-cb74-11ea-8ff5-ca3cf1fd5d67.png" width="600"> 

DEEPScreen is a high performance drug–target interaction predictor that utilizes convolutional neural networks and 2-D structural compound representations to predict their activity against intended target proteins. DEEPScreen system is composed of 704 target protein specific prediction models, each independently trained using experimental bioactivity measurements against many drug candidate small molecules, and optimized according to the binding properties of the target proteins.

DEEPScreen can be exploited in the fields of drug discovery and repurposing for in silico screening of the chemogenomic space, to provide novel DTIs which can be experimentally pursued. The source code, trained "ready-to-use" prediction models, all datasets and the results of this study are available at [DEEPScreen GitHub repository](https://github.com/cansyl/DEEPscreen). More information is available at [DEEPScreen journal paper](https://doi.org/10.1039/C9SC03414E).


<ins>**MDeePred:**</ins>

<img src="https://user-images.githubusercontent.com/13165170/88065912-da29b500-cb75-11ea-977d-d38ab648077d.png" width="600"> 

MDeePred is a deep-learning method that produces compound-target binding affinity predictions to be used for the purposes of computational drug discovery and repositioning. The method adopts the chemogenomic approach, where both the compound and target protein features are employed at the input level to model their interaction, which enables the prediction of inhibitors to under-studied or completely non-targeted proteins. In MDeePred, multiple types of protein features such as sequence, structural, evolutionary and physicochemical properties are incorporated within multi-channel 2-D vectors, which is then fed to state-of-the-art pairwise input hybrid deep neural networks to predict the real-valued compound-target protein interactions. The source code and datasets of MDeePred are available at [MDeePred GitHub repository](https://github.com/cansyl/MDeePred).  


## CROssBAR Knowledge Graphs

<img src="https://user-images.githubusercontent.com/13165170/88082149-0e0ed580-cb8a-11ea-9c12-be18cf562c9d.jpg" width="600"> 

In CROssBAR knowledge graphs, different biological components, such as;

* drugs/compounds,
* genes/proteins,
* pathways,
* phenotypes and
* diseases

are represented as nodes, and the known and predicted pairwise relationships are annotated and displayed as labeled edges. The knowledge graphs are constructed on the fly, each time the CROssBAR database is queried by the user. To convert the full output of user queries, which are initially extremely large biological networks, into biologically meaningful and interpretable representations without losing primary relationships, we applied intensive node enrichment operations. The knowledge graphs are displayed to the user as heterogeneous biological networks and their purpose is to aid biomedical research, especially in the fields of drug discovery and repositioning, by providing a concise piece of relevant biological information to the user in real time.  

For [COVID-19 Knowledge Graph use-case](https://github.com/cansyl/CROssBAR/COVID-19_KGs) please refer to the corresponding section below, for the manually constructed prototype hepatocellular carcinoma (HCC) network please visit [CROssBAR HCC network folder](https://github.com/cansyl/CROssBAR/Prototype_HCC_Network).

## CROssBAR Web-Service

<img src="https://user-images.githubusercontent.com/13165170/88083919-49120880-cb8c-11ea-8e20-d9d3850af77c.png" width="600"> 

In order to make the CROssBAR knowledge graphs (KG) available to the public in an easily interpretable way, we developed a web service and an easy to use web interface. Here, KGs are presented on a web browser as Cytoscape networks. The web service is available at: [crossbar.kansil.org](https://crossbar.kansil.org). The users can make a search for the following components individually or in combination:

* diseases/phenotypes,
* drug/drug candidate compounds,
* biological processes/pathways and
* proteins

As a result of a search requested by the user, the input containing the search term(s) in the CROssBAR database is extracted via the API and the components that have a biological relationship with this input (e.g. a signalling pathway, of which the searched protein is a member, or a disease known to occur as a result of a mutation in the protein sought, or target proteins known to interact with the searched drug molecule) are extracted from the database. For the layout of the components on th graphs, CROssBAR-layout is developed, in which biological components of a specific type are placed on circular points within fixed radii.  

For CROssBAR Web-Service use-cases please visit [CROssBAR_Web-Service folder](https://github.com/cansyl/CROssBAR/CROssBAR_Web-service).

## COVID-19 Knowledge Graphs

CROssBAR COVID-19 knowledge graphs (KGs) are constructed with aim of collecting the related data from various biomedical resources, applying filtering operations and presenting it in a coherent and standardized form to the research community. We are periodically updating our COVID-19 KGs with the new evidence that is being accumulated in our resources. On top of the data reported in source databases, our COVID-19 KGs also incorporates several new drugs (either by enrichment analysis or predicted by our deep-learning models) that can contribute to the studies on developing novel medications against SARS-CoV-2 (literature-based Investigation for predictions: Supplementary Information section 2). We also conducted simple _in vitro_ cell based wet-lab experiments (i.e., gene expression analysis) to compare its results with the computationally-inferred information.

[<ins>**Large-scale COVID-19 Knowledge Graph:**</ins>](https://crossbar.kansil.org/covid-19.php)

<img src="https://user-images.githubusercontent.com/13165170/88108825-28f54000-cbb2-11ea-8d19-462b898adaff.png" width="800">

[<ins>**Simplified COVID-19 Knowledge Graph:**</ins>](https://crossbar.kansil.org/covid-19_simplified.php)

<img src="https://user-images.githubusercontent.com/13165170/88110249-8f7b5d80-cbb4-11ea-827a-bceb3162be86.png" width="800">

The large-scale KG (987 nodes and 3639 edges) and the simplified KG (178 nodes and 298 edges). Both of these graphs reveal the most overrepresented biological processes during a SARS-CoV-2 infection, as well as, the potential treatment options with COVID-19 related pre-clinical/clinical results and our novel _in silico_ predictions (for both virus and host proteins) considering long-term drug discovery or short-term drug repositioning applications.

For more information about the COVID-19 knowledge graphs, please refer to our project paper or visit [CROssBAR COVID-19 KG folder](https://github.com/cansyl/CROssBAR/COVID-19_KGs). For information about the comparative _in vitro_ cell-based analysis together with the datasets please visit [CROssBAR wet-lab analysis folder](https://github.com/cansyl/CROssBAR/In_vitro_assays).



## License

CROssBAR (c) by CanSyL

CROssBAR is licensed under a Creative Commons Attribution 4.0 Unported License.

You should have received a copy of the license along with this work.  If not, see <http://creativecommons.org/licenses/by/4.0/>.
