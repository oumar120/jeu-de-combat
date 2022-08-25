<?php 
class Personne
{
	private $nom;
	private $degat;
	private $id;
	public const CEST_MOI=1;
	public const PERSONNE_TUE=2;
	public const PERSONNE_FRAPPE=3;

	public function __construct(array $donnee)
	{
		$this->hydrate($donnee);
	}
	public function hydrate(array $donnee){
		foreach ($donnee as $key => $value) {
			$method='set'.ucfirst($key);
			if (method_exists($this,$method)) {
				$this->$method($value);
			}
		}
	}
	public function frapper(Personne $personne){
		var_dump($this->id);
		var_dump($personne->id);
        if($this->id==$personne->id){
        	return self::CEST_MOI;
        }else{
        	$this->recevoirDegat();
        }
	}
	public function recevoirDegat(){
		$this->degat+=5;
		if($this->degat>=100){
			return self::PERSONNE_TUE;
		}else{
			return self::PERSONNE_FRAPPE;
		}
	}
	public function nomValide($nom){
		if(!empty($nom)){
			return true;
		}else{
			return false;
		}
	}
	public function getNom(){
		return $this->nom;
	}
	public function getDegat(){
		return $this->degat;
	}
	public function setNom($nom){
		if(is_string($nom)){
			$this->nom=$nom;
		}
	}
	public function setDegat($degat){
		$degat=(int) $degat;
		if($degat>=0 && $degat<100){
			$this->degat=$degat;
		}
	}
	public function setId($id){
		$id=(int) $id;
		$this->id=$id;
	}
	public function getId(){
		return $this->id;
	}
}

 ?>