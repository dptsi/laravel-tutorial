<?php
//custom exception class
class custom_exception extends Exception
{
    public function get_error_msg()
    {
        $err_msg = $this->getMessage() . " is not a valid e-mail";
        return $err_msg;
    }
}

$email = "budi@test.com";

try {
    //check email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //if email is not valid, then throw exception
        throw new custom_exception($email);
    }
    
    //check if email contains "test"
    if (strpos($email, "test") !== FALSE) {
        //if email contains "test", then throw exception
        throw new Exception("$email is a test e-mail");
    }
} catch (custom_exception $e) {
    //get custom error message
    echo $e->get_error_msg();
} catch (Exception $e) {
    //get message
    echo $e->getMessage();
}
?>
