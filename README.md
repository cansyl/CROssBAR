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

## CROssBAR Database & API

<img src="https://user-images.githubusercontent.com/13165170/88060719-42c16380-cb6f-11ea-84c4-e7f7163e152d.png" width="600"> 

![CROssBAR-DB_API](https://user-images.githubusercontent.com/13165170/88058594-59b28680-cb6c-11ea-9bf6-8d0ba3c66cf8.png) | tst tst tst tst tst tst tst tst tst tst tst tst tst tst tst tst st tst tst tst tst tst tst tst tst tst tst tst tst tst tst tst
------------ | -------------




