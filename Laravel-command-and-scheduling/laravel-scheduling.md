# Laravel Scheduling

[Kembali](readme.md)

## Latar belakang topik

Konfigurasi entri cron digunakan untuk menjadwalkan tugas-tugas seperti mengirim email atau mengunduh file dari internet di waktu tertentu. Namun, konfigurasi cron dalam sebuah server terkadang tidak nyaman. Jadwal tugas tidak lagi berada dalam source control. Untuk melihat entri cron yang ada atau menambah entri cron yang baru, perlu dilakukan SSH ke server.

Laravel menyediakan pendekatan dalam mengelola tugas-tugas terjadwal. Command scheduler milik Laravel memungkinkan penentuan jadwal command dalam aplikasi Laravel. Penggunaan scheduler ini hanya memerlukan satu entri cron dalam Server. Jadwal tugas didefinisikan dalam method **schedule** milik file **app/Console/Kernel.php**

## Konsep-konsep

Semua tugas yang terjadwal dapat didefinisikan dalam method **schedule** dari class **App\Console\Kernel**.

Di bawah ini terdapat beberapa contoh penerapan method **schedule** dalam Laravel.

### Mendefinisikan Schedule

Dalam contoh ini akan dijadwalkan closure tiap tengah malam. Dalam closure ini akan dieksekusi query database untuk membersihkan sebuah tabel.

```php
namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            DB::table('nama_tabel')->delete();
        })->daily();
    }
}
```

Artisan command **schedule:list** dapat digunakan untuk melihat semua tugas yang terjadwal serta melihat kapan tugas tersebut akan dijalankan.

```
php artisan schedule:list
```

#### Schedule Artisan Command

```php
use App\Console\Commands\SendEmailsCommand;

$schedule->command('emails:send Taylor --force')->daily();

$schedule->command(SendEmailsCommand::class, ['Taylor', '--force'])->daily();
```

#### Schedule Queued Jobs

```php
use App\Jobs\Heartbeat;

$schedule->job(new Heartbeat)->everyFiveMinutes();
```

```php
use App\Jobs\Heartbeat;

// Dispatch the job to the "heartbeats" queue on the "sqs" connection...
$schedule->job(new Heartbeat, 'heartbeats', 'sqs')->everyFiveMinutes();
```

#### Schedule Shell Command

```php
$schedule->exec('node /home/forge/script.js')->daily();
```

### Schedule Frequency Option

Terdapat banyak frekuensi task schedule yang bisa diberikan kepada sebuah tugas.

| Method                            | Description                                                   |
| --------------------------------- | ------------------------------------------------------------- |
| ->cron('* * * * *');              | Run tugas pada jadwal cron khusus                             |
| ->everyMinute();                  | Run tugas setiap menit                                        |
| ->everyTwoMinutes();              | Run tugas setiap dua menit                                    |
| ->everyThreeMinutes();	        | Run tugas setiap tiga menit                                   |
| ->everyFourMinutes();	            | Run tugas setiap empat menit                                  |
| ->everyFiveMinutes();	            | Run tugas setiap lima menit                                   |
| ->everyTenMinutes();              | Run tugas setiap sepuluh minutes                              |
| ->everyFifteenMinutes();	        | Run tugas setiap lime belas minutes                           |
| ->everyThirtyMinutes();	        | Run tugas setiap tiga puluh minutes                           |
| ->hourly();                       | Run tugas setiap jam                                          |
| ->hourlyAt(17);                   | Run tugas setiap jam 17 menit lewat jam                       |
| ->everyTwoHours();                | Run tugas setiap dua jam                                      |
| ->everyThreeHours();              | Run tugas setiap tiga hours                                   |
| ->everyFourHours();               | Run tugas setiap empat hours                                  |
| ->everySixHours();                | Run tugas setiap enam hours                                   |
| ->daily();                        | Run tugas setiap hari at midnight                             |
| ->dailyAt('13:00');               | Run tugas setiap hari at 13:00                                |
| ->twiceDaily(1, 13);              | Run tugas setiap hari at 1:00 & 13:00                         |
| ->weekly();                       | Run tugas setiap hari Minggu at 00:00                         |
| ->weeklyOn(1, '8:00');            | Run tugas setiap minggu pada Senin at 8:00                    |
| ->monthly();                      | Run tugas pada hari pertama setiap bulan pada 00:00           |
| ->monthlyOn(4, '15:00');          | Run tugas setiap bulan pada tanggal 4 di 15:00                |
| ->twiceMonthly(1, 16, '13:00');   | Run tugas setiap bulan pada tanggal 1 dan 16 di 13:00         |
| ->lastDayOfMonth('15:00');        | Run tugas pada hari terakhir bulan di 15:00                   |
| ->quarterly();                    | Run tugas pada hari pertama setiap per empat tahun pada 00:00 |
| ->yearly();                       | Run tugas pada hari pertama setiap tahun pada 00:00           |
| ->yearlyOn(6, 1, '17:00');        | Run tugas setiap tahun pada 1 Juni di 17:00                   |
| ->timezone('America/New_York');   | Mengatur zona waktu dari tugas                                |

Method-method ini dapat digabung dengan constraint tambahan untuk membuat jadwal yang lebih disesuaikan pada hari-hari tertentu dalam satu minggu.

```php
// Run once per week on Monday at 1 PM...
$schedule->call(function () {
    //
})->weekly()->mondays()->at('13:00');

// Run hourly from 8 AM to 5 PM on weekdays...
$schedule->command('foo')
          ->weekdays()
          ->hourly()
          ->timezone('America/Chicago')
          ->between('8:00', '17:00');
```

Berikut adalah daftar constraint tambahan yang dapat digunakan:

| Method                                  | Description                                             |
| --------------------------------------- | ------------------------------------------------------- |
| ->weekdays();                           | Membatasi tugas hanya pada hari kerja                   |
| ->weekends();                           | Membatasi tugas hanya pada akhir pekan                  |
| ->sundays();                            | Membatasi tugas hanya pada hari Minggu                  |
| ->mondays();                            | Membatasi tugas hanya pada hari Senin                   |
| ->tuesdays();                           | Membatasi tugas hanya pada hari Selasa                  |
| ->wednesdays();                         | Membatasi tugas hanya pada hari Rabu                    |
| ->thursdays();                          | Membatasi tugas hanya pada hari Kamis                   |
| ->fridays();                            | Membatasi tugas hanya pada hari Jumat                   |
| ->saturdays();                          | Membatasi tugas hanya pada hari Sabtu                   |
| ->days(array|mixed);                    | Membatasi tugas hanya pada hari tertentu                |
| ->between($startTime, $endTime);        | Membatasi tugas hanya pada startTime dan endTime        |
| ->unlessBetween($startTime, $endTime);  | Membatasi tugas tidak pada startTime and endTime        |
| ->when(Closure);                        | Membatasi tugas hanya berdasarkan truth test            |
| ->environments($env);                   | Membatasi tugas hanya pada environment tertentu         |

### Days Constraint

```php
$schedule->command('emails:send')
                ->hourly()
                ->days([0, 3]);
```

```php
use Illuminate\Console\Scheduling\Schedule;

$schedule->command('emails:send')
                ->hourly()
                ->days([Schedule::SUNDAY, Schedule::WEDNESDAY]);
```

### Between Time Constraint

```php
$schedule->command('emails:send')
                    ->hourly()
                    ->between('7:00', '22:00');
```

```php
$schedule->command('emails:send')
                    ->hourly()
                    ->unlessBetween('23:00', '4:00');
```

### Truth Test Constraint

```php
$schedule->command('emails:send')->daily()->when(function () {
    return true;
});
```

```php
$schedule->command('emails:send')->daily()->skip(function () {
    return true;
});
```

### Environment Constraint

```php
$schedule->command('emails:send')
            ->daily()
            ->environments(['staging', 'production']);
```

### Timezone

```php
$schedule->command('report:generate')
         ->timezone('America/New_York')
         ->at('2:00')
```

```php
protected function scheduleTimezone()
{
    return 'America/Chicago';
}
```

### Mencegah Task Overlap

Secara default, tugas terjadwal akan dijalankan meskipun instance dari tugas tersebut masih berjalan. Untuk mencegah ini, dapat digunakan method **withoutOverlapping**

```php
$schedule->command('emails:send')->withoutOverlapping();
```

Method ini berguna dalam kasus dimana terdapat banyak tugas dengan waktu eksekusi yang bervariasi. Tidak diperlukan prediksi terhadap seberapa lama tugas bekerja.
Jike diperlukan, method ini dapat ditentukan berapa menit yang diperlukan agar kunci withoutOverlapping dilepas. Secara default, kunci berakhir setelah 24 jam.

```php
$schedule->command('emails:send')->withoutOverlapping(10);
```

### Menjalankan Task Pada Satu Server

Jika scheduler berjalan pada beberapa server, tugas terjadwal dapat dibatasi agar hanya berjalan di satu server. Sebagai contoh, asumsikan terdapat tugas yang membuat laporan setiap Jumat malam. Jika task scheduler berjalan pada tiga server pekerja, tugas akan berjalan pada ketiga server dan membuat laporan tiga kali.

Untuk menandakan bahwa tugas hanya berjalan di satu server, tugas terjadwal harus didefinisikan dengan method **onOneServer**. Server pertama yang mendapat tugas ini akan mencegah server lain dalam menjalankan tugas yang sama di waktu yang sama.

```php
$schedule->command('report:generate')
                ->fridays()
                ->at('17:00')
                ->onOneServer();
```

### Background Task

Secara default, beberapa tugas terjadwal di waktu yang sama akan dijalankan secara sekuensial berdasarkan urutan yang didefinisikan di method **schedule**. Jika terdapat tugas yang berjalan lama, tugas-tugas selanjutnya akan dijalankan lebih lambat daripada yang diperkirakan. Method **runInBackground** dapat digunakan untuk menjalankan tugas di background sehingga semua tugas dapat dijalankan bersamaan.

```php
$schedule->command('analytics:report')
         ->daily()
         ->runInBackground();
```

### Maintenance Mode

Tugas terjadwal tidak akan berjalan ketika aplikasi sedang berada dalam *maintenance mode*. Hal ini agar tugas-tugas diharapkan tidak mengganggu proses pemeliharaan yang belum selesai dilakukan dalam server. Namun, tugas dapat dipaksa untuk tetap berjalan dalam maintenance mode jika diperlukan. Tugas dapat diberi method **evenInMaintenanceMode** agar dapat berjalan meskipun server sedang dalam masa pemeliharaan.

```php
$schedule->command('emails:send')->evenInMaintenanceMode();
```

## Menjalankan Scheduler

Artisan command **schedule:run** dapat digunakan untuk mengevaluasi semua tugas terjadwal dan menentukan apakah tugas tersebut perlu dijalankan sesuai waktu milik server.

Ketika menjalankan Laravel Scheduler, diperlukan satu entri konfigurasi cron pada server yang menjalankan **schedule:run** setiap menit. Servis seperti Laravel Forge dapat digunakan untuk membantu mengelola entri cron.

```
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### Menjalankan Scheduler Secara Lokal

Command **schedule:work** dapat digunakan untuk menjalankan scheduler tiap menit sampai command dihentikan (terminate).

```
php artisan schedule:work
```

## Task Output

Laravel Scheduler menyediakan beberapa **method** untuk bekerja dengan output yang dibuat oleh tugas terjadwal.

Method **sendOutputTo** dapat mengirimkan output ke sebuah file.

```php
$schedule->command('emails:send')
         ->daily()
         ->sendOutputTo($filePath);
```

Method **appendOutputTo** dapat menambahkan output pada sebuah file.

```php
$schedule->command('emails:send')
         ->daily()
         ->appendOutputTo($filePath);
```

Method **emailOutputTo** dapat mengirimkan email ke sebuah email address. Perlu dilakukan konfigurasi terhadap Laravel Email Service sebelum menjalankan method ini.

```php
$schedule->command('report:generate')
         ->daily()
         ->sendOutputTo($filePath)
         ->emailOutputTo('taylor@example.com');
```

Method **emailOutputOnFailure** akan mengirimkan email ketika scheduled Artisan atau system command diakhiri (terminate) dengan non-zero exit code.

```php
$schedule->command('report:generate')
         ->daily()
         ->emailOutputOnFailure('taylor@example.com');
```

## Task Hook

Dengan method **before** dan **after**, kode dapat diatur untuk dijalankan sebelum dan sesudah tugas terjadwal dieksekusi.

```php
$schedule->command('emails:send')
         ->daily()
         ->before(function () {
             // The task is about to execute...
         })
         ->after(function () {
             // The task has executed...
         });
```

Method **onSuccess** dan **onFailure** dapat mengatur kode agar dapat dijalankan ketika tugas terjadwal berhasil atau gagal dijalankan.

```php
$schedule->command('emails:send')
         ->daily()
         ->onSuccess(function () {
             // The task succeeded...
         })
         ->onFailure(function () {
             // The task failed...
         });
```

Jika terdapat output, isi output dapat diakses dari **after**, **onSuccess**, dan **onFailure** menggunakan instance use Illuminate\Support\Stringable sebagai argumen **$output**.

```php
use Illuminate\Support\Stringable;

$schedule->command('emails:send')
         ->daily()
         ->onSuccess(function (Stringable $output) {
             // The task succeeded...
         })
         ->onFailure(function (Stringable $output) {
             // The task failed...
         });
```

### Ping URL

Method **pingBefore** an **thenPing** dapat digunakan untuk untuk secara otomatis ping URL yang diberikan sebelum atau sesudah task dijalankan. Method ini berguna dalam mengingatkan servis luar, seperti Envoyer, bahwa tugas telah dimulai atau selesai.

```php
$schedule->command('emails:send')
         ->daily()
         ->pingBefore($url)
         ->thenPing($url);
```

Method **pingBeforeIf** an **thenPingIf** digunakan untuk ping URL jika kondisi yang diberikan return nilai true

```php
$schedule->command('emails:send')
         ->daily()
         ->pingBeforeIf($condition, $url)
         ->thenPingIf($condition, $url);
```

Method **pingOnSuccess** dan **pingOnFailure** hanya akan berjalan ketika tugas berhasil atau gagal berjalan.

```php
$schedule->command('emails:send')
         ->daily()
         ->pingOnSuccess($successUrl)
         ->pingOnFailure($failureUrl);
```

Ping method memerlukan library Guzzle HTTP.
```
composer require guzzlehttp/guzzle
```