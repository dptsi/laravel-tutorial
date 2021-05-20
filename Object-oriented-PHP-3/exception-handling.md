# Exception Handling

[Kembali](readme.md)

## Latar belakang topik

Exception handling adalah cara untuk mengatasi error dengan pendekatan object oriented. Exception handling akan mengubah alur kode jika terjadi suatu exception. Exception adalah kondisi error yang terjadi.

## Konsep-konsep

Berikut adalah alur normal saat exception terjadi:
1. state dari kode sekarang disimpan
2. eksekusi kode akan diganti ke exception handler function
3. handler dapat melanjutkan eksekusi kode dari state yang tersimpan atau terminasi eksekusi kode atau melanjutkan dari lokasi lain dalam kode

Dalam tutorial ini akan dijelaskan mengenai:
- penggunaan dasar exception
- membuat suatu exception handler
- multiple exception
- rethrow exception
- membuat top level exception handler

## Langkah-langkah tutorial

### Penggunaan dasar exception

Jika suatu exception tidak ditangkap, maka error akan dikabarkan dengan pesan "Uncaught Exception". Berikut adalah contoh exception yang tidak ditangkap.

```php
<?php

function check_num($num) {
  if($num == 0) {
    throw new Exception("value cannot be 0");
  }
  return true;
}

checkNum(0);
?> 
```

### Membuat suatu exception handler
### Multiple exception
### Rethrow exception
### Membuat top level exception handler


## Referensi
1. https://www.w3schools.com/php/php_exception.asp
2. https://www.php.net/manual/en/language.exceptions.php
3. https://stackify.com/php-try-catch-php-exception-tutorial/
4. https://jagongoding.com/web/php/menengah/penanganan-exception/
5. https://www.guru99.com/error-handling-and-exceptions.html
6. https://netgen.io/blog/modern-error-handling-in-php
7. https://www.codepolitan.com/belajar-exception-dalam-php
8. https://www.tutorialspoint.com/php/php_error_handling.htm
