//tanpa parameter
<?php
    class Employee {
        public $name;
        public $position;
        function __construct(){
            echo "\nSelamat datang di kantor!\n";
        }
        function set_name($name){
            $this->name = $name;
        }
        function get_name(){
            return $this->name;
        }
        function set_position($position){
            $this->position = $position;
        }
        function get_position(){
            return $this->position;
        }
        function __destruct(){
            echo "Sampai jumpa lagi!\n\n";
        }
    }

    $employee1 = new Employee();
    $employee1->set_name("Steven");
    $employee1->set_position("Manager");
    echo "Karyawan ini bernama ".$employee1->get_name().", dia merupakan seorang ".$employee1->get_position().".\n";
?>

//dengan parameter
<?php
    class Employee {
        function __construct($name, $position) {
            echo "\nSelamat datang di kantor!\n";
            $this->name = $name;
            $this->position = $position;
        }
        function cetak(){
            return "Karyawan ini bernama ".$this->name.", dia merupakan seorang ".$this->position.".";
        }
        function __destruct(){
            echo "\nSampai jumpa lagi!\n\n";
        }
        
    }
    $employee1 = new Employee("Steven", "Manager");
    echo $employee1->cetak();
?> 
