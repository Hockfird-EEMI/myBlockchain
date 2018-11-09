<?php

$_ENV["sumToFind"] = null;

class Chaine {

  public $data;
  public $dataHash;
  public $prev;
  public $next;
  public $prem;
  public $diff;

  public function __construct($data) {
    $this->setData($data);
    $this->diff = rand(100000,500000);
    $this->dataHash = null;
  }
  public function setData($data) {
    $this->data = $data;
  }


  public function addBlock($newBlock) {
    // On instancie le premier
    if ($this->prem == null) {
      $this->prem = $this;
      $this->prev = null;
    }

    // Si le ThisBlock a déja un next
    if ($this->next != null) {
      $this->next->addBlock($newBlock);
    } else { // Si cela est une manip "Classique"
      $this->next = $newBlock;
      $newBlock->prev = $this;
      $newBlock->prem = $this->prem;
    }
  }

  public function hashBlock() {
    // Création de variable de difficulté en plus
    // Je génère aléatoirement un chiffre entre 100.000 & 500.000
    // Depuis ce chiffre je créer une string aléatoire de ce même nombre.
    // Je hash mon bloc avec cette string
    $string = "";
    for ($i=0; $i < $this->diff; $i++) {
      $random_position = rand(0,strlen($string)-1);
      $chars = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789-_";
      $random_char = $chars[rand(0,strlen($chars)-1)];
      $string = substr($string,0,$random_position).$random_char.substr($string,$random_position);
    }

    if ($this->prev == null) { // Si cela est le premier fichier, je le hash sans le previous (Vu qu'il n'y en a pas)
      $this->dataHash = hash('sha256', $this->data . implode(getdate()) . $string);
    } else {
      $keyToCheck = $this->checkSum($this->prev->dataHash);
      if ($keyToCheck == $_ENV["sumToFind"]) { // Je vérifie si ma clé checkSum est la même que j'ai gardé en variable d'environnement.
        $this->dataHash = hash('sha256', $this->data . $this->prev->dataHash . implode(getdate()) . $string);
      } else {
        var_dump('MIDDLE MAN ! WRONG FILE !');
        die;
      }
    }
  }

  public function checkSum($dataHash) {
    // Nous nous sommes mis d'accord sur le chiffre 9 pour appliquer le modulo sur la somme de mon hash
    $arrayHash = str_split($dataHash);
    $sumHash = array_sum($arrayHash);
    $keyVal = ($sumHash%9);
    return $keyVal; // Je stock ma clé checkSum en Variable d'Environnement
  }

  public function show() { // Simple méthode show pour afficher "Proprement le contenu de mon bloc"
    var_dump("Name : ".$this->data);
    if ($this->prev == null) {var_dump("Prev : ".$this->prev);} else {var_dump("Prev : ".$this->prev->data);}
    if ($this->next == null) {var_dump("Next : ".$this->next);} else {var_dump("Next : ".$this->next->data);}
    if ($this->prem == null) {var_dump("Prem : ".$this->prem);} else {var_dump("Prem : ".$this->prem->data);}
    var_dump("Hash : ".$this->dataHash);
    var_dump("Diff : ".$this->diff);
    var_dump("--------------------------------------------------------");
  }

  static function test() { // Function test a utiliser pour générer et miner 4 blocs
    $first = new Chaine("1st Block");
    $second = new Chaine("2nd Block");
    $third = new Chaine("3rd Block");
    $fourth = new Chaine("4rth Block");

    // Hashage du 1er Bloc
    var_dump("Mining...");
    $first->hashBlock();
    $_ENV["sumToFind"] = $first->checkSum($first->dataHash);
    var_dump("1st Block Mine !");

    // Ajout et Hashage du 2eme Bloc à la chaine
    $first->addBlock($second);
    var_dump("Mining...");
    $second->hashBlock();
    $_ENV["sumToFind"] = $second->checkSum($second->dataHash);
    var_dump("2nd Block Mine !");

    // Ajout et Hashage du 3eme Bloc à la chaine
    $first->addBlock($third);
    var_dump("Mining...");
    $third->hashBlock();
    $_ENV["sumToFind"] = $third->checkSum($third->dataHash);
    var_dump("3rd Block Mine !");

    // Ajout et Hashage du 4eme Bloc à la chaine
    $first->addBlock($fourth);
    var_dump("Mining...");
    $fourth->hashBlock();
    $_ENV["sumToFind"] = $fourth->checkSum($fourth->dataHash);
    var_dump("4th Block Mine !");
    var_dump("");
    var_dump("--------------------------------------------------------");
    $first->show();
    $second->show();
    $third->show();
    $fourth->show();

    var_dump("Hurray !! All the blocks has been Mine !!");
  }

}
