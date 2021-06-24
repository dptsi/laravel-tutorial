# Unit Testing

[Kembali](readme.md)

## Latar belakang Unit Testing

Dilansir dari [Wikipedia](https://en.wikipedia.org/wiki/Software_testing#Black-box_testing), Testing pada software dilakukan untuk memberikan informasi kepada
stakeholder / developer tentang bagaimana kualitas software yang tengah dikembangkan. Testing dilakukan mencakup proses menjalankan software untuk menemukan kegagalan dan memverifikasi bahwa software itu layak digunakan. Banyak sekali jenis - jenis testing, dilihat dari "Pendekatan Testing" :

- Static, Dynamic, Passive Testing
- Explotary Approach
- White-box Testing
- Black-box Testing
- etc

Jika dilihat dari "Level Testing" :

- Unit Testing
- Integration Testing
- System Testing
- etc

Jika dilihat dari "Teknik Testing" :

- A/B Testing
- Concurrent Testing
- Usability Testing
- etc

## Konsep-konsep

Apa itu Unit Testing ? Unit testing adalah cara pengujian software dimana suatu unit individu pada suatu modul ditest untuk dicek apakah unit tersebut bisa digunakan atau tidak. Unit testing biasanya ditulis oleh Software Developer untuk memastikan unit dari modul tersebut bekerja sesuai dengan yang kita harapkan. Pada paradigma OOP, suatu unit diartikan sebagai suatu `class` atau suatu `method`.

Dengan menulis test terlebih dahulu pada unit terkecil, lalu kita cek `behavior` unit tersebut, kita dapat menulis tes untuk aplikasi yang lebih kompleks lagi.

Pada unit test, kita biasanya menggunakan `mock object`, `method stubs` untuk membantu kita melakukan test pada modul yang terisolasi. Terdapat juga library yang dapat membantu testing, seperti `JUnit` pada Java, internal lib `testing` pada Go, `cypress` pada Javascript, dll.

Terdapat juga suatu proses pengembangan software yang bernama [Test-driven Development](https://en.wikipedia.org/wiki/Test-driven_development) / TDD, dimana kita membuat banyak testcases dan menguji test cases itu ke suatu unit. Jika terdapat test yang `fail`, maka akan dianggap sebagai bug atau potential problem dan kita sebagai developer harus memperbaiki kode kita untuk membuat test tersebut `pass`

## Langkah-langkah tutorial

Pertama - tama, kita akan membuat environment testing dengan nama file `.env.testing`. Untuk tujuan dari file ini akan diberitahu lebih lanjut nantinya.

Untuk membuat Unit Testing sesuai yang ada di contoh ini, dapat menggunakan perintah berikut:

```
php artisan make:test Post/PostControllerTest --unit
```

Jika kalian melihat pada isi file PostControllerTest, maka setiap fungsi yang ada merupakan skenario yang bisa terjadi pada PostController.

Kita akan membedah setiap fungsi dan membandingkannya dengan yang ada di PostController.

```php
public function testStoreDataSuccessfullyPost()
{
    $repo = Mockery::mock(MySQLPostRepository::class);

    $repo->shouldReceive('store')->once();
    app()->instance(PostRepository::class, $repo);
    $response = $this->post('/post', [
        '_token' => csrf_token(),
        'title' => 'test',
        'description' => 'description'
    ]);
    $response->assertStatus(302);
    $response->assertRedirect('/post');
}
```

pada fungsi `testStoreDataSuccessfullyPost`, dapat dilihat bahwa kita membuat object mocking. Apa itu mocking ? Mocking adalah object tiruan yang membantu kita untuk mengendalikan value dari object tersebut pada saat testing. Kenapa kita membutuhkan mocking akan dijelaskan dibawah ini.

```php
class PostController extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
  private $postRepository;

  public function __construct(PostRepository $postRepository)
  {
    $this->postRepository = $postRepository;
  }

  public function index()
  {
    $message = __('Post::general.belajar');
    $posts = $this->postRepository->getAll();
    return view('Post::welcome', compact('message', 'posts'));
  }
```

Pada file controller diatas kita mempunyai sebuah private attribute bernama `postRepository`. Lalu di constructor kita mempunyai interface bernama `PostRepository`. Nah berhubung kita telah melakukan dependency injection di file DependencyServiceProvider.php

```php
class DependencyServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(PostRepository::class,MySQLPostRepository::class);
    }
}
```

maka `private $postRepository` akan mempunyai class implement dari `MySQLPostRepository`. Ketika kita ingin melakukan unit testing, maka tentunya ketika fungsi `index` pada PostController diakses, kita tidak ingin fungsinya benar - benar mengambil data dari database. Dapat dilihat bahwa dari syntax `$posts = $this->postRepository->getAll();`, kita akan mengambil data dari database. Sedangkan pada Unit Testing, kita hanya ingin melakukan test pada suatu object, tanpa mempengaruhi objek lain.

Disinilah kita menggunakan mocking, dimana kita membuat object tiruan agar kita bisa mengembalikan data sesuka kita. Sehingga nantinya pada saat `$posts = $this->postRepository->getAll();` dipanggil, yang terpanggil adalah object tiruan dari postRepository, bukan object MySQLPostRepository.

Balik lagi ke laptop, Jadi bagaimana kita meng-inject object tiruan itu ke dalam controller kita ?

```php
$repo = Mockery::mock(MySQLPostRepository::class);
$repo->shouldReceive('store')->once();
app()->instance(PostRepository::class, $repo);
$response = $this->post('/post', [
    '_token' => csrf_token(),
    'title' => 'test',
    'description' => 'description'
]);
$response->assertStatus(302);
$response->assertRedirect('/post');
```

pada code diatas, kita mendefinisikan object mock bertipe `MySQLPostRepository` dengan code `$repo = Mockery::mock(MySQLPostRepository::class)`. Sehingga `$repo` nantinya merupakan object mock/tiruan dari `MySQLPostRepository`. Lalu kita ingin mengendalikan fungsi `store` yang ada di `MySQLRepository`. Fungsi ini berada didalam object `MySQLPostRepository`. Kenapa kita ingin mengendalikan fungsi `store`, maka anda dapat melihat pada code dibawah ini :

```php
public function store(Request $request)
{
    $data = $request->validate([
        'title' => 'required',
        'description' => 'required',
    ]);

    $post = new Post();
    $post->title = $data['title'];
    $post->description = $data['description'];
    $post->slug = SlugService::createSlug(Post::class, 'slug', $data['title']);
    $this->postRepository->store($post->toArray());

    return redirect('/post')->with('message', 'Your post has been added!');
}
```

Kita ingin mengendalikan fungsi store di MySQLPostRepository, karena pada controller dengan fungsi `store(Request $request)`, kita akan menggunakan `$this->postRepository->store($post->toArray());`.

Arti dari `$repo->shouldReceive('store')->once();` adalah kita ingin memastikan fungsi mock `store` terpanggil sekali yang menandakan bahwa pada controller telah terjadi penyimpanan database. Lalu `app()->instance(PostRepository::class, $repo);` berarti kita menginject interface `PostRepository` dengan object mock `$repo`, sehingga interface `PostRepository` tidak mengimplementasikan `MySQLPostRepository`.

```
$response = $this->post('/post', [
    '_token' => csrf_token(),
    'title' => 'test',
    'description' => 'description'
]);
```

Nantinya, pada kode diatas berguna untuk mengirimkan request, sehingga request akan diterima dan dicek. Bagaimana untuk memastikan request kita berhasil ?

```php
$response->assertStatus(302);
$response->assertRedirect('/post');
```

Kita dapat memastikannya dengan menggunakan `assert`, dimana banyak tipe `assert`. pada test ini kita menggunakan `assertStatus` untuk mengecek apakah statusnya `302`. Kenapa statusnya `302` dan bukan `200`, karena pada PostController, kita melakukan `return redirect('/post')->with('message', 'Your post has been added!');`, sehingga yang kita lakukan adalah redirect dan redirect itu mempunyai code `302`. Kita juga mengecek apakah route redirectnya benar atau tidak dengan menggunakan `assertRedirect`.

Fungsi `testStoreDataSuccessfullyPost()` dan `testStoreDataFailedPost()` mempunyai implementasi yang hampir sama namun dengan testcase yang berbeda, dimana pada `testStoreDataFailedPost()`, kita mengirimkan

```php
$response = $this->post('/post', [
    'title' => ''
]);
```

dimana pada PostController terjadi validasi:

```php
$data = $request->validate([
    'title' => 'required',
    'description' => 'required',
]);
```

sehingga kita mengganti `assertRedirect` menjadi '/', bukan '/post'.

pada fungsi `testRenderAllPostsPage`, kita melakukan mock seperti biasa. Namun perbedaannya adalah kita mengendalikan fungsi `getAll`.

```
$posts = [];
for ($i = 0; $i < 4; $i++) {
    $post = new Post();
    $post->title = 'dasar' . $i;
    $post->description = 'description' . $i;
    $post->slug = 'dasard' . $i;
    array_push($posts, $post);
}
$repo->shouldReceive('getAll')->andReturn($posts);
```

Kita ingin agar `getAll` mengembalikan array of post yang telah kita buat data dummynya. Nantinya kita ingin memastikan apakah datanya masuk dengan menggunakan `assertSeeText`, dimana `$response->assertSeeText($posts[0]->title)`, dimana kita ingin mengecek apakah terdapat text yang mempunyai value `$posts[0]->title` pada hasil render bladenya.

Pada fungsi `testRenderShowOnePostPage()`, kita melakukan mock seperti biasa dan mengendalikan fungsi `findBySlug`, agar fungsinya melakukan return sesuai data yang kita inginkan.

```php
$post = new Post();
$post->title = 'dasar';
$post->description = 'description';
$post->slug = 'dasar';
$post->createdAt = now();
$post->updatedAt = now();
$repo->shouldReceive('findBySlug')->andReturn($post);
```

Fungsi `testRenderShowOnePostPage()` juga memiliki implementasi yang hampir sama dengan fungsi `testRenderShowEditPostPage()`

Fungsi `testEditDataSuccessfullyPost()` memiliki implementasi yang sama dengan `testEditDataFailedPost()`. Perbedaanya adalah ketika ingin mengendalikan fungsi `update`, `$repo->shouldReceive('update')->once();` kita ingin memastikan agar fungsinya terpanggil sekali, yang menandakan bahwa fungsi tersebut telah dieksekusi dan proses update berhasil.

```
$response = $this->put('/post/dasar', [
    '_token' => csrf_token(),
    'title' => 'dasar',
    'description' => 'description'
]);
```

Ketika ingin mengirim request juga, kita mengirimnya sesuai dengan yang divalidasi pada controllernya. Namun pada `testEditDataFailedPost()`, kita mengirim request sebagai berikut:

```
$response = $this->put('/post/dasar', [
    '_token' => csrf_token(),
]);
```

Sehingga nantinya request gagal karena tidak tervalidasi dengan baik.

```
public function testDeleteDataSuccessfullyPost()
{
    $repo = Mockery::mock(MySQLPostRepository::class);
    $repo->shouldReceive('deleteBySlug')->once();
    app()->instance(PostRepository::class, $repo);
    $response = $this->delete('/post/dasar');
    $response->assertStatus(302);
    $response->assertRedirect('/post');
}
```

pada fungsi diatas, kita ingin menghapus sebuah post. Kita membuat mock seperti biasa, dan kita mengatur agar fungsi `deleteBySlug` dipastikan terpanggil sekali, yang berarti bahwa fungsi tersebut telah dieksekusi dan berhasil menghapus sebuah post.
