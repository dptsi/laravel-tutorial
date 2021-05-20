# Exception Handling

[Kembali](readme.md)

## Latar belakang topik

Exception adalah kondisi yang tidak diinginkan yang mengganggu alur normal program saat program berjalan. Jika terjadi exception, maka program akan berhenti dan akan mengeluarkan error message. PHP dapat menghandle exception ini. Dengan menghandle exception, kita dapat memberikan message yang bermakna kepada pengguna, di mana error message dari sistem mungkin tidak dapat dipahami pengguna.

## Konsep-konsep

Exception handling adalah proses yang merespon suatu exception selama ekseskusi program.

Berikut adalah alur saat exception terjadi:
1. state dari kode sekarang disimpan
2. eksekusi kode akan diganti ke exception handler function
3. handler dapat melanjutkan eksekusi kode dari state yang tersimpan atau terminasi eksekusi kode atau melanjutkan dari lokasi lain dalam kode

### Keyword dalam Exception Handling

Keyword berikut digunakan dalam exception handling dalam PHP:
1. `try`

Blok `try` berisi kode yang dapat menghasilkan exception. Semua kode dalam blok `try` akan dieksekusi jika tidak terdapat exception dilemparkan.

2. `throw`

Keyword `throw` digunakan untuk memberi signal terjadinya suatu exception dan PHP runtime akan mencari statement `catch` untuk menghandle exception tersebut.

3. `catch`

Blok `catch` berfungsi untuk menangkap exception dan membuat objek yang berisi informasi exception.

4. `finally`

Kode dalam blok `finally` akan selalu dieksekusi setelah blok `try` dan `catch`, terlepas dari apakah exception sudah dilemparkan atau belum.

Berikut adalah contoh blok try catch-finally

```php
try {
    // run your code here
}
catch (exception $e) {
    //code to handle the exception
}
finally {
    //optional code that always runs
}
```

dan berikut adalah diagram yang menggambarkan cara kerja blok try catch-finally.

![image](https://user-images.githubusercontent.com/58259649/118943657-65d2c480-b986-11eb-9ca6-eed51f5c623c.png)

Dalam tutorial ini akan dijelaskan mengenai:
- penggunaan dasar exception
- membuat custom exception class
- multiple exception
- rethrow exception
- membuat top level exception handler

## Implementasi

### Penggunaan dasar exception

Jika suatu exception tidak ditangkap, maka fatal error akan muncul dengan pesan "Uncaught Exception". Berikut adalah contoh exception yang tidak ditangkap.

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

Untuk mencegah error di atas, perlu dibuat blok kode untuk menghandle exception. Berikut adalah contoh kode untuk menghandle exception.

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

Suatu exception dapat dilemparkan kembali dalam suatu blok `catch`. Berikut adalah contoh dari rethrow exception.

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
