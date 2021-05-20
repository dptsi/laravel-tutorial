# Polymorphism

[Kembali](readme.md)

## Latar belakang topik

Pada dasarnya, seluruh entitas di dunia dapat dikelompokkan dan dikategorisasikan berdasarkan persamaannya. Contoh dua pengkategorian terbesar di dunia ini adalah makhluk hidup dengan benda mati. Dikatakan makhluk hidup karena mereka membutuhkan makan dan minum, bernafas, bergerak dan lain sebagainya yang tidak ditemukan pada benda mati. Dari sanalah, kita mengetahui bahwa pada makhluk hidup memiliki kesamaan-kesamaan.

Salah satu kesamaan pada tiap makhluk hidup adalah bergerak, namun disini walaupun makhluk hidup sama-sama bergerak, pergerakan mereka berbeda-beda. Ada yang bergerak dengan melangkah, dengan terbang, dengan menyeret tubuhnya dan lain sebagainya. Alat geraknya pun berbeda, ada yang menggunakan kaki, sirip, sayap. Dari situlah kita temukan bahwa terdapat persamaan dasar dari berbagai macam entitas yang sebenarnya sama, namun perlakuannya yang mungkin berbeda pada setiap entitas.

## Konsep-konsep

<p align="center">
<img align="centre" src="https://cdn.discordapp.com/attachments/804405775988555776/844841853908287518/Untitled_Diagram_7.png">
</p>

Polimorfisme dikatakan sebagai bentuk banyak, karena suatu class dapat membentuk class-class lainnya yang memiliki persamaan mendasar. Polimorfisme merupakan sebuah konsep dimana banyak class yang memiliki method yang sama, namun pengimplementasian method tersebut pada setiap class dapat berbeda-beda. Contohnya setiap hewan bisa bersuara, namun suara tersebut berbeda tergantung pada hewannya. Kucing bisa mengeluarkan suara seperti "Meow...", Anjing  bisa mengeluarkan suara seperti "Guk... Guk..." sehingga Anjing dan Kucing dapat dikatakan sebagai bagian dari hewan. Dari contoh tersebut, bila diterapkan pada konteks OOP, bisa dikatakan bahwa class Anjing dan class Kucing merupakan extend dari sebuah class Hewan. Agar kita dapat memasukkan sebuah method yang sama pada banyak class, maka class tersebut harus diturunkan dari sebuah abstract class atau dari berbagai macam interface.

## Langkah-langkah tutorial

Langkah-langkah akan dijelaskan pada Abstract dan Interface.