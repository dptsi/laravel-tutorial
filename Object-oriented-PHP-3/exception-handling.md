# Exception Handling

[Kembali](readme.md)

## Latar belakang topik

Exception handling adalah cara untuk mengatasi error dengan pendekatan object oriented. Exception handling akan mengubah alur kode jika terjadi suatu exception. Exception adalah kondisi error yang terjadi.

## Konsep-konsep

Berikut adalah alur saat exception terjadi:
1. state dari kode sekarang disimpan
2. eksekusi kode akan diganti ke exception handler function
3. handler dapat melanjutkan eksekusi kode dari state yang tersimpan atau terminasi eksekusi kode atau melanjutkan dari lokasi lain dalam kode

Dalam tutorial ini akan dijelaskan mengenai:
- penggunaan dasar exception
- membuat custom exception class
- multiple exception
- rethrow exception
- membuat top level exception handler

## Langkah-langkah tutorial

### Penggunaan dasar exception

Jika suatu exception tidak ditangkap, maka error akan dikabarkan dengan pesan "Uncaught Exception". Berikut adalah contoh exception yang tidak ditangkap.

```php
<?php

function check_num($num)
{
    if ($num == 0) {
        throw new Exception("value cannot be 0");
    }
    return true;
}

check_num(0);
?>
```

**Output:**
```
PHP Fatal error:  Uncaught Exception: value cannot be 0 in /workspace/Main.php:6
Stack trace:
#0 /workspace/Main.php(11): check_num()
#1 {main}
  thrown in /workspace/Main.php on line 6
```

Untuk mencegah error di atas, perlu dibuat blok kode untuk menghandle exception. Blok kode tersebut harus berisi keyword:
1. `try`

Fungsi yang dapat menghasilkan exception diletakkan dalam blok `try`. Jika terjadi exception, maka exception dilempar. Jika tidak terjadi exception, maka kode akan berjalan dengan alur normal.

2. `throw`

Keyword `throw` digunakan untuk memicu exception dan setiap `throw` harus memiliki setidaknya satu blok `catch`.

3. `catch`

Blok `catch` berfungsi untuk menangkap exception dan membuat objek yang berisi informasi exception.

Berikut adalah contoh kode untuk menghandle exception.

```php
<?php

//fungsi untuk memeriksa apakah nilai num adalah nol. Jika nilai num adalah nol, maka lempar exception
function check_num($num)
{
    if ($num == 0) {
        throw new Exception("value cannot be 0");
    }
    return true;
}

//blok try
try {
    //periksa fungsi check_num()
    check_num(0);

    //alur normal
    echo 'values is not 0';
}

//blok catch
catch (Exception $e) {
    //object Exception "e" berisi informasi exception
    echo 'pesan: ' . $e->getMessage();
}
?>
```

**Output:**
```
pesan: value cannot be 0 
```

### Membuat custom exception class

Custom exception class berisi fungsi yang dipanggil saat suatu exception terjadi dan class tersebut merupakan ekstensi dari class `Exception`. Berikut adalah contoh dari custom exception class.

```php
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

$email = "budi@@test.com";

try {
    //check email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        //if email is not valid, then throw exception
        throw new custom_exception($email);
    }
} catch (custom_exception $e) {
    //get custom error message
    echo $e->get_error_msg();
}
?>
```

**Output:**
```
budi@@test.com is not a valid e-mail
```

### Multiple exception

Multiple exception digunakan untuk memeriksa beberapa kondisi. Berikut adalah contoh dari multiple exception.

```php
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
```

**Output:**
```
budi@test.com is a test e-mail
```

### Rethrow exception

Suatu exception dapat dilemparkan kembali dalam suatu blok `catch`. Berikut adalah contoh dari rethrowo exception.

```php
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
```

**Output:**
```
budi@test.com is not a valid e-mail
```

### Membuat top level exception handler

Fungsi `set_exception_handler()` berfungsi untuk menghandle semua "Uncaught Exception" menggunakan user-defined function. Berikut adalah contoh dari penggunaan `set_exception_handler()`.

```php
<?php
function my_exception($e)
{
    echo $e->getMessage();
}

set_exception_handler('my_exception');

throw new Exception('uncaught exception occurred');
?> 
```

**Output:**
```
uncaught exception occurred
```

## Referensi
1. https://www.w3schools.com/php/php_exception.asp
2. https://www.php.net/manual/en/language.exceptions.php
3. https://stackify.com/php-try-catch-php-exception-tutorial/
4. https://jagongoding.com/web/php/menengah/penanganan-exception/
5. https://www.guru99.com/error-handling-and-exceptions.html
6. https://netgen.io/blog/modern-error-handling-in-php
7. https://www.codepolitan.com/belajar-exception-dalam-php
8. https://www.tutorialspoint.com/php/php_error_handling.htm
