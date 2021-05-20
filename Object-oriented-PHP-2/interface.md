# Interface

[Kembali](readme.md)

## Latar Belakang Topik

Terkadang, pada pembuatan sebuah program atau sistem menggunakan orientasi objek, terdapat banyak class yang membutuhkan satu atau lebih method yang sebenarnya adalah sama, namun perlakuannya berbeda pada setiap class.

Contohnya terdapat 3 class yang merepresentasikan merk smartphone, yaitu {Xiaovo, Realsus, Vime}. Ketiga class tersebut memiliki berbagai method yang sama, seperti contohnya ketiga smartphone tersebut dapat dinyalakan atau memiliki tombol power on. Namun, walapun ketiga smartphone tersebut sama-sama memilki method tersebut, setiap brand pasti menunjukkan sesuatu yang berbeda ketika tombol power on ditekan dan smarthpone dinyalakan, entah yang ditampilkan logo bertuliskan brandnya, atau logo robot, ataupun logo jamur.

Lalu contoh lain mungkin ketika ketiga smartphone tersebut telah menyala, pastinya smartphone tersebut akan menampilkan sebuah tampilan dari Sistem Operasinya. Nah walaupun ketiganya memiliki sistem operasi, namun ketiga smartphone tersebut memiliki tampilan yang berbeda pada setiap sistem operasinya. Katakanlah ada yang menggunakan Realsus OS, Sentuhlucu OS, ataupun MUIU OS.

Tentunya semua smartphone, baik itu Xiaovo, Realsus ataupun Vime harus dapat dinyalakan, maka mau tidak mau pada smarthone tersebut harus mengandung method atau fungsi power on. Lalu semua smartphone juga tentunya harus memiliki User Interface untuk memudahkan pengguna dalam menjalankan Sistem Operasinya, walaupun mungkin pada setiap OS atau ROM pada smartphone berbeda-beda tampilannya. Hal inilah yang mendasari penggunaan Interface.

## Konsep-Konsep

Konsep interface mirip dengan sebuah 'kontrak' yang dilakukan ketika kita mengimplementasikan interface pada suatu class. Dikatakan demikian, karena ketika suatu class tersebut telah mengimplementasikan sebuah interface, maka seluruh method yang ada di interface harus diimplementasikan ulang pada class tersebut.

Isi dari interface ini adalah sebuah method kosong yang bisa berisi parameter ataupun tidak. Dapat dikatakan interface ini hanya 'setor' nama method yang nantinya harus diimplementasikan ulang atau dirincikan pada class yang mengimplementasikan interface tersebut.

## Langkah-Langkah Tutorial

### Langkah Pertama
kita Buat interface mirip dengan sebuah class, hanya saja tidak perlu menuliskan class, melainkan langsung menuliskan interface dilanjutkan dengan nama interfacenya.
```php
<?php
interface PowerOn{
	//isi dari interface PowerOn
 }

interface OperatingSystem{
	//Isi dari interface OperatingSystem
 }
?>
```

### Langkah Kedua
Lalu kita isi interface dengan sebuah method kosong. Method boleh memiliki parameter ataupun tidak.
```php
<?php
interface PowerOn{
	public function turn_on();
 }

interface OperatingSystem{
	public function show_ui();
 }
?>
```

### Langkah Ketiga
Untuk mengimplementasikan interface tersebut dalam suatu class, kita menggunakan keyword implements.
```php
<?php
interface PowerOn{
	public function turn_on();
 }

interface OperatingSystem{
	public function show_ui();
 }
   
 class Xiaovo implements PowerOn, OperatingSystem{
	//... Isi class Xiaovo
 }
   
 class Realsus implements PowerOn, OperatingSystem{
	//... Isi class Xiaovo
 }
 
 class Vime implements PowerOn, OperatingSystem{
	//... Isi class Xiaovo
 }
?>
```
Dengan begini, maka masing-masing class Xiaovo, Realsus, maupun Vime harus memuat function turn_on() dan show_ui().

### Langkah Keempat
Lalu untuk mengimplementasikan function tersebut kedalam masing-masing class, penulisannya seperti kita menuliskan fungsi biasanya.
```php
<?php
interface PowerOn{
	public function turn_on();
 }

interface OperatingSystem{
	public function show_ui();
 }
   
 class Xiaovo implements PowerOn, OperatingSystem{
	public function turn_on() {
	  return "<Gambar Kodok>Xiaovo sedang booting...";
	}
 
	public function show_ui() {
	  return "Welcome to MUIU OS!";
	}
 }
   
 class Realsus implements PowerOn, OperatingSystem{
	public function turn_on() {
	  return "<Tulisan SnSV>Realsus sedang booting...";
	}
 
	public function show_ui() {
	  return "Welcome to Realsus OS!";
	}
 }
 
 class Vime implements PowerOn, OperatingSystem{
	public function turn_on() {
	  return "<Gambar Robot>Vime sedang booting...";
	}
	
	public function show_ui() {
	  return "Welcome to Sentuh Lucu OS!";
	}
 }
?>
```
### Langkah Kelima
Buat Objek untuk setiap class, lalu cek hasil dari method `turn_on()` dan `show_ui` pada tiap class menggunakan echo di dalam syntax paragraf html
```php
$Xiaovo = new Xiaovo();
$Vime = new Vime();
$Realsus = new Realsus();

echo "<p>Xiaovo : ".$Xiaovo->turn_on()."</p>";
echo "<p>Vime : ".$Vime->turn_on()."</p>";
echo "<p>Realsus : ".$Realsus->turn_on()."</p>";

echo "<p>Ketika booting"."</p>";

echo "<p>Xiaovo : ".$Xiaovo->show_ui()."</p>";
echo "<p>Vime : ".$Vime->show_ui()."</p>";
echo "<p>Realsus : ".$Realsus->show_ui()."</p>";
``` 
## Hasil

Berikut adalah output yang ditampilkan pada browser.

![Hasil output](https://cdn.discordapp.com/attachments/804405775988555776/844820831875235910/unknown.png)

## Kesimpulan
Penggunaan interface pada beberapa class, memungkinkan untuk class-class tersebut menggunakan suatu method atau fungsi yang sama, namun memiliki perlakuan yang berbeda. Hal tersebut membantu programmer untuk mengingatkan fungsi dasar apa saja yang harus ada dan wajib diisikan pada suatu class yang mengimplementasikan interface, agar tidak terdapat suatu fungsi yang miss atau gagal dijalankan oleh user.