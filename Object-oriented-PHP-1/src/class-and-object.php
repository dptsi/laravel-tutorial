<?php
class Employee {
  public $name;
  public $position;
  public $salary;

  function set_name($name) {
    $this->name = $name;
  }

  function get_name() {
    return $this->name;
  }
}

$employee1 = new Employee();
$employee1->set_name("Steven");
echo $employee1->get_name();
echo "\n";

$employee2 = new Employee();
$employee2->set_name("Rudolf");
echo $employee2->{"get_name"}();
echo "\n";

$employee3 = new Employee();
$employee3->set_name("Smith");
$funcGetNama = "get_name";
echo $employee3->{$funcGetNama}();
echo "\n";

$employee4 = new Employee();
$employee4->name = "Kevin";
echo $employee4->name;
echo "\n";

?>
