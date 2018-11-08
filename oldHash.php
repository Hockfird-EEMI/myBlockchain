<?php

// DATA
$index = 0;
$data = "Hello World";
$previousData = 0;
$hashType = 'sha256';

function hasher($hashType, $index, $data, $peviousData) {
  $newHash = hash($hashType, $index . $data . $peviousData);
  return $newHash;
}

function getPreviousData($index, $previousData) {
  if (isset($_COOKIE['pData'])) {
      $previousData = $_COOKIE['pData'];
      return $previousData;
  } else if ($previousData == 0){
    return $previousData;
  } else {
    var_dump('MIDDLE MAN ! WRONG FILE !');
  }
}

function addBlock($index, $data, $i) {
  $index = $index + $i;
  var_dump($index);
  return $index;
}

function mining($index, $data, $previousData) {
  $latestData = getPreviousData($index);
  if ($previousData != $latestData) {
    var_dump('MIDDLE MAN ! WRONG FILE !');
  } else {
    var_dump(hasher($hashType, $index, $data, $previousData));
    setcookie($previousData);
  }
}

// for ($i=0; $i < 10; $i++) {
//   addBlock($index, $data, $i);
// }
// var_dump($index);
// die;
getPreviousData(1);
newBlock();

?>
