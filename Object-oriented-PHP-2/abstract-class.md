# Abstract Class

[Kembali](readme.md)

## Latar belakang topik

Setiap entitas yang ada di dunia nyata memungkinkan untuk memiliki child yang memiliki beberapa atribut maupun metode yang sama sehingga seolah-olah entitas-entitas tersebut membentuk sebuah abstraksi. Seperti contoh terdapat entitas kucing dan anjing, keduanya sama-sama memiliki atribut jumlah kaki, lalu mereka juga memiliki method untuk bersuara. Karena mereka memiliki entitas dan atribut yang sama, maka didesainlah sebuah kelas parent yang memuat entitas dan atribut tersebut, katakanlah class hewan.

Dalam membuat parent class, akan digunakan class berjenis abstract dengan tujuan agar class hewan tidak dapat diinstansiasi. Tentu saja kita hal itu karena class tersebut hanyalah abstraksi saja, bukan sebuah entitas konkrit. Selain itu, kita juga ingin agar method untuk bersuara harus dioverride oleh childnya karena diasumsikan setiap hewan pasti memiliki suara yang unik.

## Konsep-konsep

Konsep abstraksi adalah bagaimana menyembunyikan implementasi dan hanya menunjukkan fungsionalitas. Salah satu cara untuk menggunakan abstraksi pada bahasa PHP adalah dengan menggunakan abstract class. Abstract class disini memungkinkan kita untuk membuat child class saling berbagi atribut dan method, akan tetapi mewajibkan child classnya untuk mengimplementasikan apa yang belum diimplementasikan pada abstract class sehingga cocok untuk kasus hewan di atas dimana diasumsikan setiap hewan memiliki suara yang unik sehingga child class selalu mengoverride method pada parent classnya.

## Langkah-langkah tutorial

### Langkah pertama

Buat class `Hewan` dengan atribut `jumlah_kaki` dan method abstrak `bersuara()`, pastikan atribut tersebut `private` karena kita tidak ingin atribut tersebut diubah setelah objek diinstansiasi, namun dapat diakses valuenya menggunakan getter.

```php
<?php

abstract class Hewan
{
    private int $jumlah_kaki;

    public function __construct(int $jumlah_kaki)
    {
        $this->jumlah_kaki = $jumlah_kaki;
    }

	public abstract function bersuara(): string;

	public function getJumlahKaki(): int {
		return $this->jumlah_kaki;
	}
}
```

### Langkah kedua

Buat class `Kucing` dan `Anjing` yang meng-*extend* class `Hewan`. Pastikan constructornya memanggil constructor parent dengan menyesuaikan jumlah kakinya. Setelah itu buat method yang mengoverride method `bersuara()` pada parentnya.

```php
<?php

class Kucing extends Hewan {
	public function __construct()
	{
		parent::__construct(4);
	}

	public function bersuara(): string
	{
		return "Meong";
	}
}

class Anjing extends Hewan {
	public function __construct()
	{
		parent::__construct(4);
	}

	public function bersuara(): string
	{
		return "Guk-guk";
	}
}
```
### Langkah ketiga

Instansiasi kedua child class tersebut dan simpan ke variabel yang relevan.

```php
<?php

$kucing = new Kucing();
$anjing = new Anjing();
```

### Langkah keempat

Cek hasil dari method `bersuara()` dan `getJumlahKaki()` menggunakan echo didalam syntax paragraf html.

```php
echo "<p>Kucing bersuara: ".$kucing->bersuara()."</p>";
echo "<p>Jumlah kaki kucing : ".$kucing->getJumlahKaki()."</p>";

echo "<p>Anjing bersuara: ".$anjing->bersuara()."</p>";
echo "<p>Jumlah kaki anjing : ".$anjing->getJumlahKaki()."</p>";
```

## Hasil

Berikut adalah output yang ditampilkan pada browser.

![Hasil output](https://cdn.discordapp.com/attachments/798177440425181256/843329843403161621/unknown.png)

## Kesimpulan

Penggunaan abstract class memungkinkan untuk berbagi beberapa atribut maupun method dengan childnya namun child harus mengoverride abstract function yang dideklarasikan pada abstract class. Hal itu memungkinkan programmer untuk membuat user tidak dapat menginstansiasi objek abstrak dan mengimplementasikan cara yang berbeda untuk beberapa fungsi.
