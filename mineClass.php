<?php

class Chaine {

  public $data;
  public $prev;
  public $next;
  public $prem;
  public $diff;

  public function __construct($data) {
    $this->setData($data);
  }

  public function setData($data) {
    $this->data = $data;
  }


  public function add($newBlock) {
    if ($this->prem == null) {
      $this->prem = 0;
    }

    $this->next = $newBlock;
    $newBlock->prev = $this;
    $newBlock->prem = $this->prem;
  }

  public function show() {
    var_dump($this);
  }

}

$first = new Chaine("1st Block");
$second = new Chaine("2nd Block");
$third = new Chaine("3rd Block");

$first->add($second);
$second->add($third);
$first->show();
$second->show();
$third->show();
