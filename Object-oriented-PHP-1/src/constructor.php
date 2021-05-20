<?php
class Employee {
  public $name;
  public $position;

  function __construct($name, $position) {
    $this->name = $name;
    $this->position = $position;
  }

}

$employee1 = new Employee("Steven", "Manager");
echo "Nama Karyawan: ".$employee1->name;
echo "\n";
echo "Posisi: ".$employee1->position;
echo "\n";

?>
