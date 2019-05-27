<?php

use Illuminate\Database\Seeder;

class MemberTableSeeder extends Seeder
{
    public function run()
    {
        factory(App\Models\Member::class, 100)->create();
    }
}