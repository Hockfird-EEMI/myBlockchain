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
    $this->diff = rand(1000,5000);
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
    $string = "";
    for ($i=0; $i < $this->diff; $i++) {
      $random_position = rand(0,strlen($string)-1);
      $chars = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789-_";
      $random_char = $chars[rand(0,strlen($chars)-1)];
      $string = substr($string,0,$random_position).$random_char.substr($string,$random_position);
    }

    if ($this->prev == null) {
      $this->dataHash = hash('sha256', $this->data . implode(getdate()) . $string);
    } else {
      $keyToCheck = $this->checkSum($this->prev->dataHash);
      if ($keyToCheck == $_ENV["sumToFind"]) {
        $this->dataHash = hash('sha256', $this->data . $this->prev->dataHash . implode(getdate()) . $string);
      } else {
        var_dump('MIDDLE MAN ! WRONG FILE !');
        die;
      }
    }
  }

  public function checkSum($dataHash) {
    // Nous nous sommes mis d'accord sur le chiffre 9
    $arrayHash = str_split($dataHash);
    $sumHash = array_sum($arrayHash);
    $keyVal = ($sumHash%9);
    return $keyVal;
  }

  public function show() {
    var_dump($this->data);
    if ($this->prev == null) {var_dump($this->prev);} else {var_dump($this->prev->data);}
    if ($this->next == null) {var_dump($this->next);} else {var_dump($this->next->data);}
    if ($this->prem == null) {var_dump($this->prem);} else {var_dump($this->prem->data);}
    var_dump($this->dataHash);
    var_dump($this->diff);
    var_dump("--------------------------------------------------------");
  }

}

$first = new Chaine("1st Block");
$second = new Chaine("2nd Block");
$third = new Chaine("3rd Block");
$fourth = new Chaine("4rth Block");
$fifth = new Chaine("5fth Block");

// Hashage du 1er Bloc
$first->hashBlock();
$_ENV["sumToFind"] = $first->checkSum($first->dataHash);

// Ajout et Hashage du 2eme Bloc à la chaine
$first->addBlock($second);
$second->hashBlock();
$_ENV["sumToFind"] = $second->checkSum($second->dataHash);

// Ajout et Hashage du 3eme Bloc à la chaine
$first->addBlock($third);
$third->hashBlock();
$_ENV["sumToFind"] = $third->checkSum($third->dataHash);

// Ajout et Hashage du 4eme Bloc à la chaine
$first->addBlock($fourth);
$fourth->hashBlock();
$_ENV["sumToFind"] = $fourth->checkSum($fourth->dataHash);

$first->show();
$second->show();
$third->show();
$fourth->show();
// $fifth->show();
