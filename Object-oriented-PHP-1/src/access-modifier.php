<?php
class Employee {
  public $name;
  protected $position;
  private $salary;

  function set_name($name) {
    $this->name = $name;
    echo $this->get_name();
    echo "\n";
  }

  public function get_name() {
    return $this->name;
  }

  public function set_position($position) {
    $this->position = $position;
    echo $this->get_position();
    echo "\n";
  }

  protected function get_position() {
    return $this->position;
  }

  public function set_salary($salary) {
    $this->salary = $salary;
    echo $this->get_salary();
    echo "\n";
  }

  private function get_salary() {
    return $this->salary;
  }
}

$employee = new Employee();

$employee->name = "Steven";
echo $employee->name;
echo "\n";

// akan muncul error
$employee->position = "Manager";
echo $employee->position;
echo "\n";

// akan muncul error
$employee->salary = 100.95;
echo $employee->salary;
echo "\n";

$employee->set_name("Steve");
echo $employee->get_name();
echo "\n";

$employee->set_position("Programmer");
// akan muncul error
echo $employee->get_position();
echo "\n";

$employee->set_salary(100.45);
// akan muncul error
echo $employee->get_salary();
echo "\n";

?>
