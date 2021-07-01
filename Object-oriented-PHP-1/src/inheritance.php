<?php
class Employee {
  public $name;
  protected $salary;

  function set_name($name) {
    $this->name = $name;
  }

  function get_name() {
    return $this->name;
  }

  function set_salary($salary) {
    $this->salary = $salary;
    echo $this->get_salary();
    echo "\n";
  }

  protected function get_salary() {
    return $this->salary;
  }
}

class HourlyEmployee extends Employee{
  private $hours;
  private $salaryperhours;

  function set_hours($hours) {
    $this->hours = $hours;
  }

  function get_hours() {
    return $this->hours;
  }

  // override
  function set_salary($salaryperhours) {
    $this->salaryperhours = $salaryperhours;
    $this->salary = $this->hours * $this->salaryperhours;
    echo $this->get_salary();
    echo "\n";
  }
}

$employee = new HourlyEmployee();

$employee->set_name("Steven");
echo $employee->name;
echo "\n";

$employee->set_hours(16);
echo $employee->get_hours();
echo "\n";

$employee->set_salary(0.5);

?>
