<?php declare(strict_types = 1);

interface PowerOn{
	public function turn_on();
 }

interface OperatingSystem{
	public function show_ui();
 }
   
 class Xiaovo implements PowerOn, OperatingSystem{
	public function turn_on() {
	  return "[Gambar Kodok] Xiaovo sedang booting...";
	}
 
	public function show_ui() {
	  return "Welcome to MUIU OS!";
	}
 }
   
 class Realsus implements PowerOn, OperatingSystem{
	public function turn_on() {
	  return "[Tulisan SnSV] Realsus sedang booting...";
	}
 
	public function show_ui() {
	  return "Welcome to Realsus OS!";
	}
 }
 
 class Vime implements PowerOn, OperatingSystem{
	public function turn_on() {
	  return "[Gambar Robot] Vime sedang booting...";
	}
	
	public function show_ui() {
	  return "Welcome to Sentuh Lucu OS!";
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