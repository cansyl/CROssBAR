# CROssBAR Web-service

Online CROssBAR web-service is developed to make the integrated biomedical data available to the public in an easily interpretable, interactive way via a graphical user interface. Knowledge graphs are presented visually on web-browsers as Cytoscape networks. Users can make searches with CROssBAR components by simply typing the names or ids of the query terms individually or in combination, to obtain relevant sub-graphs, constructed on-the-fly. Here, we provide 2 simple use-cases for the CROssBAR web-service:

<img src="https://user-images.githubusercontent.com/13165170/114306284-24780b00-9ae4-11eb-839d-2a0f7fc3021c.png" width="900"> 

## Web-service Data Exploration Example 1

To provide an example about one of the many possible uses of the CROssBAR system, we explore the relation between a drug (trifluoperazine) and a disease (gastric cancer), to make a very quick and rough evaluation on the potential repurposing of this drug towards the disease of interest. Trifluoperazine is an approved antipsychotic agent mainly used in the treatment of schizophrenia. To construct the corresponding knowledge graph, we queried the CROssBAR-WS with this drug and disease entries and selected the number of nodes to be incorporated to the graph (from each biomedical component) as 20. The resulting graph is shown below.

Trifluoperazine exerts its antipsychotic effect with the blockage of dopamine D2 receptor. This relation is shown in the graph, where trifluoperazine binds to the DRD2 gene/protein node and is associated with the dopaminergic synapse pathway. In the KG, trifluoperazine also has other approved targets such as CALM1, ADRA1A and TNNC1 proteins (approved drug-target interaction edges have green colour), and these proteins are associated with calcium signalling pathway. Moreover, DRD2 and CALM1 are associated with the rap1 signalling pathway, as well. Both calcium and rap1 signalling pathways have other gene/protein associations such as ERBB2, KRAS, and CDH1, which are further associated with the gastric cancer disease. In the light of these relations, trifluoperazine can be explored via additional in silico and wet-lab studies, in terms of its potential to become a repurposed agent for the treatment of gastric cancer, which may show its activity on gastric cancer cells via calcium and rap1 signalling pathways.

<img src="https://user-images.githubusercontent.com/13165170/88282469-49c4af00-ccf2-11ea-888a-4afb745ba98a.png" width="600"> 

<img src="https://user-images.githubusercontent.com/13165170/88282490-58ab6180-ccf2-11ea-8668-64d6bec14b9b.png" width="600"> 

Both the json formatted network file and the csv formatted core-protein-centric relation table of use-case 1 can be found above. It is also possible to interactively display this KG on the [CROssBAR web-service](https://crossbar.kansil.org) by clicking "Example Search: #1" and starting the construction of knowledge graph.


## Web-service Data Exploration Example 2

A drug search on CROssBAR can also be utilized towards identifying new drug-like compounds with similar target-based bioactivities. This kind of exploration can be useful for medicinal chemists and other researchers working on drug discovery. In this example, we query [Sorafenib](https://www.drugbank.ca/drugs/DB00398) on CROssBAR-WS, which is a drug approved for the treatment of primary kidney and primary  liver cancers, to construct the knowledge graph that includes a relevant set of biomedical data. An interesting observation on the resulting KG are the compound nodes: [CHEMBL272938](https://www.ebi.ac.uk/chembl/compound_report_card/CHEMBL272938/) and [CHEMBL3910171](https://www.ebi.ac.uk/chembl/compound_report_card/CHEMBL3910171/), which contain high number of shared targets with Sorafenib (5 out 10 of the approved target proteins of Sorafenib are also the targets of these compounds) indicated by the bioassay-based interactions (blue colored edges on the graph) for CHEMBL272938 and by computationally predicted interactions (red colored edges on the graph) for CHEMBL3910171. It is also important to note that, the other half of the approved targets of Sorafenib could also be shared with these compounds; however, we currently do not have further information about it. High overlap between these targets indicate the potential of these compounds to be an alternative for Sorafenib. It is also important to note that this result could not be obtained with a conventional molecular similarity search, as Sorafenib, CHEMBL272938 and CHEMBL3910171 have highly dissimilar structures (neither a substructure search, nor a pairwise molecular similarity search -with the minimum similarity threshold of 40%- on the ChEMBL database could not detect any similarity between these three molecules).

<img src="https://user-images.githubusercontent.com/13165170/88481562-dbd3ee00-cf64-11ea-8b2c-83feb359446c.png" width="600"> 

Both the json formatted network file and the csv formatted core-protein-centric relation table of use-case 2 can be found above.

More information about these use-cases can be found in our project paper. Information regarding the CROssBAR web-service and its user interface can be found at: https://crossbar.kansil.org/tutorial.php. The entire CROssBAR web-service including the web-site and the underlying API queries can be found in the folder entitled: "CROssBAR_Web-service_Source/crossbar" above.
