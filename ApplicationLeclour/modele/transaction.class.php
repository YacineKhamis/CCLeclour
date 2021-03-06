<?php
require_once "connection.class.php";
class Transaction {
	private $id_transac;
	private $lib_transac;
	private $date_transac;
	private $heure_transac;
	private $id_utilisateur;
	private $id_carte;
	public function __construct($id_transac, $lib_transac, $date_transac, $heure_transac, $id_utilisateur, $id_carte) {
		$this->id_transac = $id_transac;
		$this->lib_transac = $lib_transac;
		$this->date_transac = $date_transac;
		$this->heure_transac = $heure_transac;
		$this->id_utilisateur = $id_utilisateur;
		$this->id_carte = $id_carte;
	}
	
	// //////////////////////////////
	// retourne une carte grace au code barre
	// //////////////////////////////
	public static function rechercheIdCarte($numCarte) {
		// verification a faire
		$conn = Connection::get ();
		$result = null;
		
		// requete sql preparé
		$request = $conn->prepare ( "SELECT id_transac, lib_transac, date_transac, heure_transac, id_utilisateur, id_carte FROM transaction WHERE id_carte=:id_carte" );
		$request->execute ( array (
				'id_carte' => $numCarte 
		) );
		
		while ( $row = $request->fetch () ) {
			$result [] = $row;
		}
		foreach ( $result as $index => $ligne ) {
			$transactions [$index] = new Transaction ( $result [$index] ['id_transac'], $result [$index] ['lib_transac'], $result [$index] ['date_transac'], $result [$index] ['heure_transac'], $result [$index] ['id_utilisateur'], $result [$index] ['id_carte'] );
		}
		
		return $transactions;
	}
	
	// Tous les getters
	public function getIdTransac() {
		return $this->id_transac;
	}
	public function getLibTransac() {
		return $this->lib_transac;
	}
	public function getDateTransac() {
		return $this->date_transac;
	}
	public function getHeureTransac() {
		return $this->heure_transac;
	}
	public function getIdUtilisateur() {
		return $this->id_utilisateur;
	}
	public function getIdCarte() {
		return $this->id_carte;
	}
	
	//creer une transaction
	public static function creer($lib_transac, $id_utilisateur, $id_carte) {
		$conn = Connection::get ();
		
		// requete d'insertion
		$t=time();

		$request = $conn->prepare ( "INSERT INTO transaction (id_transac, lib_transac, date_transac, heure_transac, id_utilisateur, id_carte) VALUES (:id_transac, :lib_transac, :date_transac, :heure_transac, :id_utilisateur, :id_carte)" );
		$request->execute ( array (
				'id_transac' => "",
				'lib_transac' => $lib_transac,
				'date_transac' => date("Y-m-d",$t),
				'heure_transac' => date("H:i:s",$t),
				'id_utilisateur' => $id_utilisateur,
				'id_carte' => $id_carte 
		) );
	}
	
	public static function recherchePeriode($dateDeb, $dateFin){
		$conn = Connection::get();
		
		$select = $conn->query("SELECT id_transac, date_transac FROM transaction WHERE DATEDIFF(date_transac, '".$dateDeb."') >=0 AND DATEDIFF('".$dateFin."',date_transac) >=0");
		$result = array();
		
		while ( $row = $select->fetch() ) {
			$result[] = $row;
		}

		return $result;
	
	}
	
	public static function recherchePeriode2($dateDeb, $dateFin){
		$conn = Connection::get();
		
		$select = $conn->query("SELECT COUNT(*) FROM transaction WHERE DATEDIFF(date_transac, '".$dateDeb."') >=0 AND DATEDIFF('".$dateFin."',date_transac) >=0");
		return $select->fetch();
	
	}
	
	
}
