<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('posts')->insert([
            'name' => 'post by admin',
            'user_id' => '1',
        ]);
        DB::table('posts')->insert([
            'name' => 'post by user',
            'user_id' => '2',
        ]);
    }
}
