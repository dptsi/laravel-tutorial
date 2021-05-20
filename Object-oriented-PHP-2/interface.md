Latar Belakang
Terkadang, pada pembuatan sebuah program atau sistem menggunakan orientasi objek, terdapat banyak class yang membutuhkan satu atau lebih method yang sebenarnya adalah sama, namun perlakuannya berbeda pada setiap class.

Contohnya terdapat 3 class yang merepresentasikan merk smartphone, yaitu {Xiaovo, Realsus, Vime}. Ketiga class tersebut memiliki berbagai method yang sama, seperti contohnya ketiga smartphone tersebut dapat dinyalakan atau memiliki tombol power on. Kita beri nama methodnya turn_on. Namun, walapun ketiga smartphone tersebut sama-sama memilki method turn_on, setiap brand pasti menunjukkan sesuatu yang berbeda ketika turn_on. Entah itu logo bertuliskan brandnya, atau logo robot, ataupun logo jamur.

Lalu contoh lain mungkin ketika ketiga smartphone tersebut telah menyala, pastinya smartphone tersebut akan menampilkan sebuah tampilan dari Sistem Operasinya. Nah walaupun ketiganya memiliki sistem operasi, namun ketiga smartphone tersebut memiliki tampilan yang berbeda pada setiap sistem operasinya. Katakanlah ada yang menggunakan Realvo OS, Sentuhlucu OS, ataupun MUIU OS.

Konsep-Konsep
Konsep interface mirip dengan sebuah 'kontrak' yang dilakukan ketika kita mengimplementasikan interface pada suatu class. Dikatakan demikian, karena ketika suatu class tersebut telah mengimplementasikan sebuah interface, maka seluruh method yang ada di interface harus diimplementasikan ulang pada class tersebut.

Isi dari interface ini adalah sebuah method kosong yang bisa berisi parameter ataupun tidak. Dapat dikatakan interface ini hanya 'setor' nama method yang nantinya harus diimplementasikan ulang atau dirincikan pada class yang mengimplementasikan interface tersebut.

Langkah-Langkah Tutorial
Pertama-tama, kita membuat interface mirip dengan sebuah class, hanya saja tidak perlu menuliskan class, melainkan langsung menuliskan interface dilanjutkan dengan nama interfacenya.
```
<?php
interface smartphone
{
   //...isi dari interface smartphone
}
?>
```

Lalu kita isi interface dengan sebuah method kosong. Method boleh memiliki parameter ataupun tidak.
```
<?php
interface smartphone{
   public function turn_on();
   public function show_ui();
}
?>
```

Untuk mengimplementasikan interface tersebut dalam suatu class, kita menggunakan keyword implements.
```
<?php
interface smartpone{
   public function turn_on();
   public function show_ui();
}
  
class Xiaovo implements smartphone{
   //... isi dari class Xiaovo
}
  
class Realsus implements smartphone{
   //... isi dari class Realsus
}

class Vime implements smartphone{
   //... isi dari class Vime
}
?>
```
Dengan begini, maka masing-masing class Xiaovo, Realsus, maupun Vime harus memuat function turn_on() dan show_ui().

Lalu untuk mengimplementasikan function tersebut kedalam masing-masing class, penulisannya seperti kita menuliskan fungsi biasanya.
```
<?php
interface smartphone{
   public function turn_on();
   public function show_ui();
}
  
class Xiaovo implements smartphone{
   public function turn_on() {
     return "<Gambar Kodok>Xiaovo sedang booting..."
   }

   public function show_ui(); {
     return "Welcome to Xiaovo OS!";
   }
}
  
class Realsus implements smartphone{
   public function turn_on() {
     return "<Tulisan SnSV>Realsus sedang booting..."
   }

   public function show_ui(); {
     return "Welcome to Sentuh Lucu OS!";
   }
}

class Vime implements smartphone{
   public function turn_on() {
     return "<Gambar Robot>Vime sedang booting..."
   }

   public function show_ui(); {
     return "Welcome to MUIU OS!";
   }
}
?>
```
