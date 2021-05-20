<?php declare(strict_types = 1);

interface Smartphone{
	public function turn_on();
	public function show_ui();
 }
   
 class Xiaovo implements Smartphone{
	public function turn_on() {
	  return "<Gambar Kodok>Xiaovo sedang booting...";
	}
 
	public function show_ui() {
	  return "Welcome to Xiaovo OS!";
	}
 }
   
 class Realsus implements Smartphone{
	public function turn_on() {
	  return "<Tulisan SnSV>Realsus sedang booting...";
	}
 
	public function show_ui() {
	  return "Welcome to Sentuh Lucu OS!";
	}
 }
 
 class Vime implements Smartphone{
	public function turn_on() {
	  return "<Gambar Robot>Vime sedang booting...";
	}
 
	public function show_ui() {
	  return "Welcome to MUIU OS!";
	}
 }

$Xiaovo = new Xiaovo();
$Vime = new Vime();
$Realsus = new Realsus();

echo "<p>Xiaovo : ".$Xiaovo->turn_on()."</p>";
echo "<p>Vime : ".$Vime->turn_on()."</p>";
echo "<p>Realsus : ".$Realsus->turn_on()."</p>";

echo "<p>Ketika booting"."</p>";

echo "<p>Xiaovo : ".$Xiaovo->show_ui()."</p>";
echo "<p>Vime : ".$Vime->show_ui()."</p>";
echo "<p>Realsus : ".$Realsus->show_ui()."</p>";