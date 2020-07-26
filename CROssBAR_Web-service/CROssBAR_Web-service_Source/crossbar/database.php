<?php
	/*
	session_start();
	if(!isset($_SESSION['login']))
		$_SESSION['login'] = 0;
	error_reporting(0);
	*/
	class database {

		public $baglan;
		public function __construct(){
			try {
				 $this->baglan = new PDO("mysql:host=;dbname=", "", "", array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			} catch ( PDOException $e ){
				print $e->getMessage();
			}
		}

		public function table($tbl,$type=PDO::FETCH_ASSOC){
			$sonuc = $this->baglan->query("SELECT * FROM $tbl");
			return $sonuc->fetchAll($type);
			#return $sonuc->fetch($type); # tek sonuc donduruyor
		}

		public function tableOrdered($tbl,$area,$ord='asc',$type=PDO::FETCH_ASSOC){
			$sonuc = $this->baglan->query("SELECT * FROM $tbl order by $area $ord");
			return $sonuc->fetchAll($type);
		}

		# kosullu tablo cekmek icin...
		public function tableWhere($tbl,$alan,$type=PDO::FETCH_ASSOC){
			$query = $this->baglan->prepare('SELECT * FROM '.$tbl.' WHERE '.key($alan).' = :'.key($alan));
			$sonuc = $query->execute($alan);
			return $query->fetchAll($type);
		}
		# kosullu tablodan tek bir column cekmek icin...
		public function tableWhereColumn($tbl,$column,$alan,$type=PDO::FETCH_ASSOC){
			$query = $this->baglan->prepare('SELECT '.$column.' FROM '.$tbl.' WHERE '.key($alan).' = :'.key($alan));
			$sonuc = $query->execute($alan);
			return $query->fetchAll($type);
		}
		# OR kosullu tablo cekmek icin...
		public function tableMultiWhereOr($tbl,$cols,$type=PDO::FETCH_ASSOC){
			$query = $this->baglan->prepare('SELECT * FROM '.$tbl.' WHERE source = :source or target = :target');
			$sonuc = $query->execute($cols);
			return $query->fetchAll($type);
		}
		# AND kosullu tablo cekmek icin...
		public function tableMultiWhereAnd($tbl,$cols,$type=PDO::FETCH_ASSOC){
			$query = $this->baglan->prepare('SELECT * FROM '.$tbl.' WHERE source = :source and target = :target');
			$sonuc = $query->execute($cols);
			return $query->fetchAll($type);
		}

		# AND-OR kosullu tablo cekmek icin...
		public function tableMultiWhereOrAnd($tbl,$cols,$type=PDO::FETCH_ASSOC){
			$query = $this->baglan->prepare('SELECT * FROM '.$tbl.' WHERE (source = :source1 and target = :target1) OR (source = :source2 and target = :target2)');
			$sonuc = $query->execute($cols);
			return $query->fetchAll($type);
		}

		# tabloyu sayfalama yardımıyla çekmek için
		public function tableLimited($tbl,$skip,$limit,$type=PDO::FETCH_ASSOC){
			$query = $this->baglan->prepare("SELECT * FROM $tbl LIMIT :start , :sayi");
			$query->bindValue(':start', (int) $skip, PDO::PARAM_INT);
			$query->bindValue(':sayi', (int) $limit, PDO::PARAM_INT);
			$query->execute();
			return $query->fetchAll($type);

		}

		# tabloyu sayfalama yardımıyla çekmek için
		public function tableWhereLimited($tbl,$alan,$skip,$limit,$type=PDO::FETCH_ASSOC){
			$query = $this->baglan->prepare('SELECT * FROM '.$tbl.' WHERE '.key($alan).' = :'.key($alan).' LIMIT :start , :sayi');
			$query->bindValue(':start', (int) $skip, PDO::PARAM_INT);
			$query->bindValue(':sayi', (int) $limit, PDO::PARAM_INT);
			$query->bindValue(':'.key($alan), $alan[key($alan)], PDO::PARAM_INT);
			$query->execute();
			return $query->fetchAll($type);
		}

		// 2 kategoriyi birden kontrol etmek icin....
		public function tableWhereLimitedMultiKt($tbl,$kt,$ktIkincil,$skip,$limit,$type=PDO::FETCH_ASSOC){
			$query = $this->baglan->prepare('SELECT * FROM '.$tbl.' WHERE '.key($kt).' = :'.key($kt).' or '.key($ktIkincil).' = :'.key($ktIkincil).' LIMIT :start , :sayi');
			$query->bindValue(':start', (int) $skip, PDO::PARAM_INT);
			$query->bindValue(':sayi', (int) $limit, PDO::PARAM_INT);
			$query->bindValue(':'.key($kt), $kt[key($kt)], PDO::PARAM_INT);
			$query->bindValue(':'.key($ktIkincil), $ktIkincil[key($ktIkincil)], PDO::PARAM_INT);
			$query->execute();
			return $query->fetchAll($type);
		}
		
		public function tableWhereBetween($tbl,$between,$alan='',$type=PDO::FETCH_ASSOC){
			if(is_array($alan)){
				$query = $this->baglan->prepare('SELECT * FROM '.$tbl.' WHERE '.key($alan).' = :'.key($alan).' AND fiyat BETWEEN :ilk AND :son');
				$query->bindValue(':'.key($alan), $alan[key($alan)], PDO::PARAM_INT);
			}else{
				$query = $this->baglan->prepare('SELECT * FROM '.$tbl.' WHERE fiyat BETWEEN :ilk AND :son');
			}
			$query->bindValue(':ilk', $between['ilk'], PDO::PARAM_INT);
			$query->bindValue(':son', $between['son'], PDO::PARAM_INT);
			$query->execute();
			return $query->fetchAll($type);
		}

		public function tableWhereBetweenMultiKt($tbl,$between,$alan,$kt,$type=PDO::FETCH_ASSOC){

			$query = $this->baglan->prepare('SELECT * FROM '.$tbl.' WHERE ('.key($alan).' = :'.key($alan).' or '.key($kt).' = :'.key($kt).') AND fiyat BETWEEN :ilk AND :son');
			$query->bindValue(':'.key($alan), $alan[key($alan)], PDO::PARAM_INT);
			$query->bindValue(':'.key($kt), $kt[key($kt)], PDO::PARAM_INT);

			$query->bindValue(':ilk', $between['ilk'], PDO::PARAM_INT);
			$query->bindValue(':son', $between['son'], PDO::PARAM_INT);
			$query->execute();
			return $query->fetchAll($type);
		}

		# tek kosullu tek deger cekmek icin...
		public function select($tbl,$alan,$type=PDO::FETCH_ASSOC){
			$query = $this->baglan->prepare('SELECT * FROM '.$tbl.' WHERE '.key($alan).' = :'.key($alan));
			$query->execute($alan);
			$sonuc = $query->fetchAll($type);
			return reset($sonuc); # dizinin ilk satirini dondur...
		}

		public function selectByColumn($tbl,$cols,$type=PDO::FETCH_ASSOC){
			$columns = '';
			foreach($cols as $col){
				$columns .= '`'.$col.'`,';
			}
			$columns = substr($columns,0,-1);
			$query = $this->baglan->query("SELECT $columns FROM $tbl");
			$sonuc = $query->fetchAll($type);
			return $sonuc;
		}

		public function total($tbl,$kosul=''){
			if(is_array($kosul)){
				$query = $this->baglan->prepare('SELECT count(*) FROM '.$tbl.' WHERE '.key($kosul).' = :'.key($kosul));
				$query->execute($kosul);
			}else{
				$query = $this->baglan->query("SELECT count(*) FROM $tbl");
			}
			$sonuc = $query->fetchAll(PDO::FETCH_NUM);
			return $sonuc[0][0];
		}

		public function totalMultiKt($tbl,$kosul,$kt){
			$query = $this->baglan->prepare('SELECT count(*) FROM '.$tbl.' WHERE '.key($kosul).' = :'.key($kosul).' or '.key($kt).' = :'.key($kt));
			$query->bindValue(':'.key($kosul), $kosul[key($kosul)], PDO::PARAM_INT);
			$query->bindValue(':'.key($kt), $kt[key($kt)], PDO::PARAM_INT);
			$query->execute();
			$sonuc = $query->fetchAll(PDO::FETCH_NUM);
			return $sonuc[0][0];
		}

		public function insert($tbl,$datas){
			$sorgu ="INSERT INTO $tbl SET";
			foreach($datas as $key => $data){
				$sorgu.=" $key = :$key, ";
			}
			$this->baglan->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );
			$query = $this->baglan->prepare(substr($sorgu,0,-2));
			$query->execute($datas);
			#print_r($query->errorInfo());
			return $this->baglan->lastInsertId(); # eklenen kaydin
		}

		public function update($tbl,$datas,$kosul){
			$sorgu ="UPDATE $tbl SET";
			foreach($datas as $key => $data){
				$sorgu.=" $key = :$key, ";
			}
			$sorgu = substr($sorgu,0,-2).' WHERE '.key($kosul).' = :'.key($kosul);
			$datas[key($kosul)] = reset($kosul);
			$query = $this->baglan->prepare($sorgu);
			$query->execute($datas);
		}

		public function delete($tbl,$datas){
			$sorgu = "DELETE FROM $tbl WHERE";
			foreach($datas as $key => $data){
				$sorgu.=" $key = :$key";
			}
			$query = $this->baglan->prepare($sorgu);
			$query->execute($datas);
		}

		public function search($column,$search){
			# $column değişkeni hangi tablodan hangi column geleceğini belirtiyor.
			# $search değişkeni hangi column da ne aranacağını belirtiyor.
			# SELECT * FROM `diseases` WHERE `disease` LIKE 'Sor%' 
			//$column['gene_to_acc_tax'] = 'gene';
			$sorgu = 'SELECT '.$column[key($column)].' FROM '.key($column).' WHERE '.key($search).' LIKE ? limit 10';
			$query = $this->baglan->prepare($sorgu);
			$query->execute(array('%'.$search[key($search)].'%'));
			return $query->fetchAll(PDO::FETCH_ASSOC);
		}
		public function search_gene_with_tax($gene,$tax){
			$tax_array = array();
			$tax_query = '';
			foreach($tax as $i => $t){
				$tax_query .= 'tax=:tax'.$i .' OR ';
				$tax_array['tax'.$i] = $t;
			}
			$tax_query = substr($tax_query,0,-3);
			/*
			print_r($tax_array);
			ECHO $tax_query; die();
			*/
			#$sorgu = 'SELECT gene FROM gene_to_acc_tax WHERE gene LIKE :gene and ('.$tax_query.') limit 10';
			#$sorgu = 'SELECT acc,gene FROM gene_to_acc_tax WHERE gene LIKE :gene and ('.$tax_query.') limit 20';
			$sorgu = 'SELECT acc,gene FROM gene_to_acc_tax WHERE gene LIKE :gene and ('.$tax_query.')';
			$query = $this->baglan->prepare($sorgu);
			#$tax_array['gene'] = '%'.$gene.'%';
			$tax_array['gene'] = $gene.'%';
			#$query->execute(array('gene'=>'%'.$gene.'%','tax'=>$tax));
			$query->execute($tax_array);
			return $query->fetchAll(PDO::FETCH_ASSOC);
		}

		public function fetch_gene_with_tax($gene,$tax){
			$tax_array = array();
			$tax_query = '';
			foreach($tax as $i => $t){
				$tax_query .= 'tax=:tax'.$i .' OR ';
				$tax_array['tax'.$i] = $t;
			}
			$tax_query = substr($tax_query,0,-3);
			$sorgu = 'SELECT * FROM gene_to_acc_tax WHERE (gene =:gene or acc =:gene) and ('.$tax_query.')';
			/*
			echo $gene;
			print_r($tax);
			echo $sorgu;
			*/
			$query = $this->baglan->prepare($sorgu);
			$tax_array['gene'] = $gene;
			$query->execute($tax_array);
			return $query->fetchAll(PDO::FETCH_ASSOC);
		}

		public function run($sorgu,$datas){
			$query = $this->baglan->prepare($sorgu);
			$query->execute($datas);
			return $query->fetchAll(PDO::FETCH_ASSOC);
		}

		public function __destruct(){ //bağlantıyı sonlandırma
			$this->baglan = null;
		}

	}
?>
