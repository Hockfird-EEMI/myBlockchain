<?php

$index = 0;
$data = "Hello World";
$previousData = 0;
$hashType = 'sha256';
$numberOfFiles = rand(3, 15);
$arrayOfHashes = [];

// Méthode de Hash
function hasher($hashType, $index, $data, $previousData) {
  $newHash = hash($hashType, $index . $data . $previousData);
  return $newHash;
}

// Rajoute un caractère en guise de difficulté
function addDifficulty($string, $difficulty) {
  for ($i=0; $i < $difficulty; $i++) {
    $random_position = rand(0,strlen($string)-1);
    $chars = "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789-_";
    $random_char = $chars[rand(0,strlen($chars)-1)];
    $string = substr($string,0,$random_position).$random_char.substr($string,$random_position);
  }
  return $string;
}

// A revoir
$firstData = hasher($hashType, $index, $data, $previousData);
array_push($arrayOfHashes, $firstData);

for ($i=0; $i < $numberOfFiles; $i++) {

  // Difficulty
  $difficulty = rand(1,5);
  $data = addDifficulty($data, $difficulty);

  // Index
  $index = $i;
  $zindex =  $index - 1;

  // Get Previous Data
  if ($index == 0) {
    $previousData = $arrayOfHashes[$i];
  } else if ($index == $i){
    $previousData = $arrayOfHashes[$zindex];
  } else {
    var_dump('MIDDLE MAN ! WRONG FILE !');
    die;
  }


  $dataHash = hasher($hashType, $index, $data, $previousData);
  array_push($arrayOfHashes, $dataHash);

}

print_r($arrayOfHashes);
