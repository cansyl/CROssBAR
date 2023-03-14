CROssBAR integrated knowledge graphs (KG) are giant biological/biomedical relationship networks created by merging numerous query-specific small-scale KGs based on their shared nodes and edges. We have created two versions of CROssBAR integrated knowledge graphs (KGs):
- To construct **the CROssBAR-generic-KG version**, we performed a bulk search using CROssBAR web service to obtain independent KGs for all reviewed human proteins listed in the UniProtKB/Swiss-Prot database (i.e., 20,173 protein entries). We queried each protein entry with parameters "predictions=0, num_of_drugs=100, num_of_compounds=100," while other parameters were set to default. The KGs were then merged, and duplicate nodes and edges were removed.
- The second integrated KG, **CROssBAR-generic-KG_all-pchembl-bioactivities-added**, is the extended version of the CROssBAR-generic-KG. It includes all bioactivity edges with pchembl values, which are eliminated due to enrichment analysis during the construction of the initial query-based small-scale KGs. 

The CROssBAR-generic-KG.zip and CROssBAR-generic-KG_all-pchembl-bioactivities-added.zip files contain the node and edge files for both versions of the integrated KGs in tsv file format.

Please see the tables below for node and edge statistics. Note that the only difference between the two versions is the number of "compound" nodes and "Chembl" (bioactivity) edges.

| **Node Type**	| **Size** |
| ------------- | -------- |  
Compound	| 135,441 <sup>*</sup> / 423,009 <sup>**</sup> 
Protein	| 23,554 
HPO	| 9,165
Drug	| 5,858
Disease	| 3,815
Pathway	| 3,764
kegg_Disease 	| 1,879
kegg_Pathway 	| 248
TOTAL	| 183,724 <sup>*</sup> / 471,292 <sup>**</sup>
</td><td>
  
| **Edge Type**	| **Size** |
| ------------- | -------- | 
Chembl	| 169,622 <sup>*</sup> / 630,251 <sup>**</sup> 
PPI	| 93,203
HPO	| 40,330
Pathway	| 36,534
hpodis	| 24,673
kegg_path_prot	| 19,474
Drug	| 16,739
Disease	| 7,309
kegg_dis_prot	| 5,940
kegg_dis_path	| 1,686
kegg_dis_drug	| 308
TOTAL	| 415,818 <sup>*</sup> / 876,447 <sup>**</sup> 

<sup>*</sup> CROssBAR-generic-KG, <sup>**</sup> CROssBAR-generic-KG_all-pchembl-bioactivities-added

