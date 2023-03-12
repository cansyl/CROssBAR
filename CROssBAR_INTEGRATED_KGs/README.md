We have created two versions of CROssBAR integrated knowledge graphs (KGs):
- To construct **the CROssBAR-generic-KG version**, we performed a bulk search using CROssBAR web service to obtain KGs for all reviewed human proteins in UniProt (i.e., 20,173 protein entries in SwissProt). We queried each protein entry with parameters "predictions=0, num_of_drugs=100, num_of_compounds=100," while other parameters were set as default. The KGs were then merged, and duplicate edges were removed.
- The second integrated KG, **CROssBAR-generic-KG_all-pchembl-bioactivities-added**, is the extended version of the CROssBAR-generic-KG. It includes all bioactivity edges with pchembl values, which are eliminated due to enrichment analysis during the construction of query-based KGs. 

The CROssBAR-generic-KG.zip and CROssBAR-generic-KG_all-pchembl-bioactivities-added.zip files contain the node and edge files for both versions of the integrated CROssBAR KGs in tsv file format.

Please see the table below for statistics on the nodes and edges in these KGs. Note that the only difference between the two versions is the number of "compound" nodes and "Chembl" edges.


