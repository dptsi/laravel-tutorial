<?php

namespace Database\Seeders;

use App\Modules\Post\Core\Domain\Model\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $fakePost = Post::factory()->count(5)->make();
        DB::table('posts')->insert($fakePost);
    }
}
