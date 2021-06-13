# Topik 1

[Kembali](readme.md)

## Latar belakang topik

Dalam melakukan pemrograman, sebaiknya sebuah class hanya melakukan sebuah tugas spesifik. Namun terkadang kita sering memasukkan banyak sekali fungsi ke dalam class sehingga apabila nantinya ada bagian class yang ingin diubah, banyak bagian lain yang ikut berubah. Menggunakan event dan listener mempermudah kita mengatur agar class kita menjalankan satu tugas.

## Konsep-konsep

Event adalah sebuah peristiwa/aktivitas yang terjadi pada aplikasi kita. Event merupakan sebuah cara untuk mengetahui apabila sebuah peristiwa terjadi (sebagai trigger). Listener adalah sebuah class yang akan menunggu perintah untuk dijalankan dari event yang menugaskan listener tersebut. Sebuah event dapat memiliki lebih dari 1 listener.

## Langkah-langkah tutorial

### Langkah pertama

Misal: Buat class `Contoh`

```php
<?php


namespace DummyNamespace;


class Contoh
{
    public function fungsi($request)
    {
        ...
    }

}
```

### Langkah kedua