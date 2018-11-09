<?php

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
      // Le Next du Nouveau block devient le next du ThisBlock
      // $newBlock->next = $this->next;
      // Le prev du ThisBlock devient le Nouveau Block
      // $this->next->prev = $newBlock;
      // Le Next du ThisBlock devient le Nouveau Block
      // $this->next = $newBlock;

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

    if ($this->prev->data == null) {
      $this->dataHash = hash('sha256', $this->data . implode(getdate()) . $string);
    } else {
      $this->dataHash = hash('sha256', $this->data . $this->prev->data . implode(getdate()) . $string);
    }
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

$first->addBlock($second);
$first->addBlock($third);
$first->addBlock($fourth);
// $fourth->addBlock($fifth);


// $third->hashBlock();
$first->show();
$second->show();
$third->show();
// $fourth->show();
// $fifth->show();

// 1st Block | NULL | 4rth Block | 1stBlock
// 2 Block | 4 | 3 | 1stBlock
// 3 Block | 2 | NULL | 1stBlock
// 4 Block | 1 | 2 | 1stBlock
