<?php 
class Manager
{
	private $db;
	public function __construct($db)
	{
		$this->setDb($db);
	}
   public function addP(Personne $perso){
     $q=$this->db->prepare("insert into personne set nom=?,degat=?");
     $q->execute(array($perso->getNom(),0));
     $perso->hydrate(array(
     	'id'=>$this->db->lastInsertId(),
         'degat'=>0));
   }
   public function updateP(Personne $perso){
       $q=$this->db->prepare("update personne set degat=?");
       $q->execute(array($perso->getNom()));
   }
   public function getP(Personne $perso){
   	if($perso->getId()!==null){
       $q=$this->db->prepare("select * from personne where id=?");
       $q->execute(array($perso->getId()));
       $donnee=$q->fetch();
       return new Personne($donnee);
   }elseif($perso->getNom()!==null){
       $q=$this->db->prepare("select * from personne where nom=?");
       $q->execute(array($perso->getNom()));
       $donnee=$q->fetch();
       return new Personne($donnee);
   }
   	  
    }
   public function deleteP(Personne $perso){
       $q=$this->db->prepare("delete from personne where id=?");
       $q->execute(array($perso->getId()));
   }
   public function count(){
   	$q=$this->db->prepare("select count(*) from personne");
   	$q->execute();
   	$donnee=$q->fetchColumn();
   	return $donnee;
   }
   public function getList(){
   	$perso=array();
   	$q=$this->db->prepare("select * from personne");
   	$q->execute();
   	while($donnee=$q->fetch()){
   		$perso[]=new Personne($donnee);
   	}
   	return $perso;
   }
   public function personneExist(Personne $perso){
   	if(!empty($perso->getId()))
      {
      	$q=$this->db->prepare("select * from personne where id=?");
      	$q->execute(array($perso->getId()));
      	if($q->rowcount()>0)
      		return true;
      }elseif(!empty($perso->getNom())){
      	$q=$this->db->prepare("select * from personne where nom=?");
      	$q->execute(array($perso->getNom()));
      	if($q->rowcount()>0){
      		return true;
      	}
      } 
   }
   public function setDb($db){
   	$this->db=$db;
   }
}

 ?>