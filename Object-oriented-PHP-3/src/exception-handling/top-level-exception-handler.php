<?php
function my_exception($e)
{
    echo $e->getMessage();
}

set_exception_handler('my_exception');

throw new Exception('uncaught exception occurred');
?> 
