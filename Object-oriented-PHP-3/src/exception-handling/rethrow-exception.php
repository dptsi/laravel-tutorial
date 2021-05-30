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
    try {
        //check if email contains "test"
        if (strpos($email, "test") !== FALSE) {
            //if email contains "test", then throw exception
            throw new Exception("$email is a test e-mail");
        }
    } catch (Exception $e) {
        //rethrow
        throw new custom_exception($email);
    }
} catch (custom_exception $e) {
    //get custom error message
    echo $e->get_error_msg();
}
?>
