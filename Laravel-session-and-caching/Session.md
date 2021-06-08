# Session

[Kembali](readme.md)

## Latar belakang topik

Misal: jelaskan mengenai latar belakang, alasan penggunaan, dll.

## Konsep-konsep
Session menyediakan cara untuk menyimpan informasi pengguna di beberapa permintaan di server. File Konfigurasi session disimpan di **config/session/php**. Secara default, Laravel dikonfigurasi untuk menggunakan **file** session driver yang kompatibel dengan banyak aplikasi. Konfigurasi Session driver menentukan dimana session data akan disimpan untuk setiap permintaan. Jenis backend popular untuk session driver antara lain :
1. **File** - sessions yang disimpan di **storage/framework/sessions**.
2. **Cookie** - sessions yang disimpan di secure, encrypted cookies.
3. **Database** - sessions yang disimpan di relational database.
4. **Memcached/redis** - sessions yang disimpan di salah satu penyimpanan berbasis cache.
5. **Dynamodb** - sessions yang disimpan di **AWS DynamoDB**
6. **Array** - sessions yang disimpan di PHP array.


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
