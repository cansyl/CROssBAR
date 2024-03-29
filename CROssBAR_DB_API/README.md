# CROssBAR Database and API

CROssBAR database is constructed by collecting relational data from various biomedical data resources UniProt, IntAct, InterPro, Reactome, Ensembl, DrugBank, ChEMBL, PubChem, KEGG, OMIM, Orphanet, Gene Ontology, Experimental Factor Ontology (EFO) and Human Phenotype Ontology (HPO) by persisting specific data attributes with the implementation of logic rules, in MongoDB collections. Open access CROssBAR-DB can be queried via our public RESTful API, which provides a multi-faceted view of the stored data.

<ins>**CROssBAR-DB schema**</ins>**:**

<img src="https://user-images.githubusercontent.com/13165170/113633185-29b50000-9675-11eb-8a24-643cd8762538.png">

<ins>**CROssBAR-DB statistics**</ins>**:**

<img src="https://user-images.githubusercontent.com/13165170/113633466-b233a080-9675-11eb-95a8-4a150eb3cb05.png" width="450">

<ins>**Full-scale work-flow of the CROssBAR-API queries for the construction of knowledge graphs**</ins>**:**

Here, the finalized filtered dataset of each biological component (i.e., genes/proteins, diseases, phenotypes, drugs, compounds and pathways) is shown with a shape surrounded by a black frame. The graph is built using the entities in these datasets, together with their inter-component relations.

<img src="https://user-images.githubusercontent.com/13165170/88443673-e9348f80-ce21-11ea-9321-9eb68cb224b0.png">
