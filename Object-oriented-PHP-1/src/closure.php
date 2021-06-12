<?php
$print_message1 = function(){
  echo "Hello!\n";
};
$print_message1();

$get_message = function($name){
  return "Hello, " . $name . "!\n";
};
echo $get_message("Steven");

$message = "Hello!";
$print_message2 = function() use ($message){
  echo $message;
  echo "\n";
};
$print_message2();

$fruits = ["a" => "Apel", "b" => "Belimbing", "c" => "Cerry"];

array_walk($fruits, function($value, $key) {
 echo $key . ". "  . $value . "\n";
});

$print_fruits = function($value, $key) {
 echo $key . ". "  . $value . "\n";
};
array_walk($fruits, $print_fruits);

array_walk($fruits, function($value, $key, $fruits) {
 echo $key . ". "  . $value . "\n";
 var_dump($fruits);
}, $fruits);

?>
