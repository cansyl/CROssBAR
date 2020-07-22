# Prototype: Hepatocellular Carcinoma Network

We constructed a prototype disease, hepatocellular carcinoma (HCC), network using CROssBAR integrated and some additional data resources and by setting multiple enrichment based filters to include only the most relevant biomedical entities. Later, this workflow have been automatized to generate the knowledge graphs and to visualize them using CytoScape web plug-in, through the CROssBAR web-service. Below, we describe the steps applied to construct the prototype network.


**Workflow for the construction of the network:**

The prototype network model was created in 7 main steps:

**1. The selection of HCC related genes:**

- KEGG (H00048): 20 genes

- OMIM (Phenotype MIM 114550): 9 genes

- OpenTargets (EFO_0000182): 18 genes (with score > 0.2 «genetic associations» )

- TCGA_HCC: 34 genes (expert knowledge)

- 61 HCC related genes in total

<img src="https://user-images.githubusercontent.com/8128032/61721044-86bd3500-ad70-11e9-9df0-50c7bc51a329.png" width="500">

---------------------------------------------------------------------------------------------------------------------------------------


**2. The determination of protein-protein interactions (PPIs):** 

- STRING application on CytoScape 

- PPIs with a confidence score >= 0.95

- 45 PPIs between 31 proteins

<img src="https://user-images.githubusercontent.com/8128032/61718989-b702d480-ad6c-11e9-849b-0245642cca10.png" width="500" height="200">

---------------------------------------------------------------------------------------------------------------------------------------

**3. The selection of compounds interacting with HCC related genes:**

**3a. Known interactions from DrugBank** 

- 63 interactions between 21 genes and 57 compounds 

- Edge color: Green   

- Node color: Red (approved and investigational drugs)

![image](https://user-images.githubusercontent.com/8128032/61719015-ca15a480-ad6c-11e9-9c05-031d449f6d5c.png)

---------------------------------------------------------------------------------------------------------------------------------------


**3b. Experimentally measured interactions from PubChem + ChEMBL (ExCAPE dataset)**

- Compounds with pXC50 >= 5.0 were labelled as active.

- For each compound, enrichment score was calculated with hypergeometric test, based on ratios of active & inactive datapoints of compounds for HCC network genes and in the overall ExCAPE dataset (ChEMBL+PubChem) targets.

- Only compounds with enrichment score > 1 were considered

- Top 5 compounds, which are not similar to each other, were selected based on enrichment scores 

- 26 interactions between 11 genes and 12 compounds

- Edge color: Blue   

- Node color: Orange (drug-like compounds) 

![image](https://user-images.githubusercontent.com/8128032/61719047-dbf74780-ad6c-11e9-86b0-a871eeac768c.png)

---------------------------------------------------------------------------------------------------------------------------------------


**3c. Predicted interactions from DEEPScreen**

- Predicted interactions were retrieved from DEEPSreen predictions 

- For each compound, enrichment score was calculated with hypergeometric test, based on ratios of active & inactive datapoints of compounds for HCC network genes and in the overall DEEPScreen targets.

- Only compounds with enrichment score > 1 were considered

- Top 5 compounds, which are not similar to each other, were selected based on enrichment scores 

- 25 interactions between 5 genes and 23 compounds 

- Edge color: Red   

- Node color: Orange (if not a drug) 

![image](https://user-images.githubusercontent.com/8128032/61719081-ec0f2700-ad6c-11e9-98e9-de9050fd71d5.png)

---------------------------------------------------------------------------------------------------------------------------------------


**4. The determination of HCC related pathways and their gene associations:** 

- Signaling pathways associated with HCC disease pathway (hsa05225) in KEGG

- STRING enrichment application on CytoScape 
  - FDR cutoff = 0.05
  - KEGG signaling pathways >= 5 enriched genes
     
<img src="https://user-images.githubusercontent.com/8128032/61722515-19f76a00-ad73-11e9-80f9-fab13384c512.png" width="600">

- 66 interactions between 22 genes and 10 pathways 

![image](https://user-images.githubusercontent.com/8128032/61719251-40b2a200-ad6d-11e9-982b-89bd90f87011.png)

---------------------------------------------------------------------------------------------------------------------------------------


**5. The determination of other diseases associated with HCC related genes:** 

- Associations between these genes and other diseases

**5a. KEGG Disease Terms**

- STRING enrichment application on CytoScape 
  - FDR cutoff = 0.05
  - KEGG diseases >= 10 enriched genes

<img src="https://user-images.githubusercontent.com/8128032/61721937-0dbedd00-ad72-11e9-8cba-61602a5c8049.png" width="600">

- 72 interactions between 27 genes and 5 diseases

![image](https://user-images.githubusercontent.com/8128032/66081772-68926300-e571-11e9-80d6-623297aa43da.png)

**5b. EFO Disease Terms** 

- EFO disease terms were retrieved from GWAS (Genome-Wide Association Studies) Catalog (https://www.ebi.ac.uk/gwas/docs/file-downloads). 

- For each EFO term, enrichment score and p-value was calculated based on ratios of EFO terms in HCC genes and in the overall GWAS gene set.

- Only EFO terms with enrichment score > 20 and p-value < 0.005 were considered.

- EFO terms belonging to "disease" root were selected and associated with related genes.

- 35 interactions between 20 genes and 7 EFO disease terms

![image](https://user-images.githubusercontent.com/8128032/66081718-3f71d280-e571-11e9-813e-6288a508d06c.png)

---------------------------------------------------------------------------------------------------------------------------------------


**6. The determination of associations between pathways and diseases:** 

- Retrieved from KEGG pathways of the network diseases

- 26 interactions between 10 pathways and 5 diseases

![image](https://user-images.githubusercontent.com/8128032/66081837-8b247c00-e571-11e9-867b-8ca2d066d34a.png)

---------------------------------------------------------------------------------------------------------------------------------------


**7. The determination of associations between genes and HPO terms:**

- HPO terms were retrieved from Human Phenotype Ontology database (https://hpo.jax.org/app/)

- For each HPO term, enrichment score and p-value were calculated with hypergeometric test, based on ratios of active & inactive datapoints of HPO terms for HCC network genes and in the overall HPO targets.

- Only HPO terms with enrichment score > 65 and p-value < 10^-5 were considered

- Top 10 HPO terms, which have not parent-child relationship with each other, were selected and associated with related genes

- 120 interactions between 22 genes and 10 HPO terms

<img src="https://user-images.githubusercontent.com/8128032/61719445-99823a80-ad6d-11e9-845a-9e7eba619146.png" width="600">

---------------------------------------------------------------------------------------------------------------------------------------


**The finalized prototype network includes 185 nodes (i.e., genes, compounds, pathways, KEGG and EFO diseases, HPO terms) and 478 edges (i.e., interactions) in total.**

![image](https://user-images.githubusercontent.com/8128032/66083489-15baaa80-e575-11e9-8c6a-231fe02e9cb8.png)

<img src="https://user-images.githubusercontent.com/13165170/61747367-00b7e300-ad9e-11e9-85e4-19c0c907e4e6.png" width="500">

---------------------------------------------------------------------------------------------------------------------------------------



## How to load the network on CytoScape:

To load the Hepatocellular Carcinoma Prototype Network on CytoScape;

- You may directly open the session file (Hepatocellular_Carcinoma_Network.cys) via CytoScape application or (if it does not work):

- You may open a new session on CytoScape and import the network file (Hepatocellular_Carcinoma_Network.xgmml) as File -> Import -> Network -> File
