<?php

use Illuminate\Database\Seeder;

class GroupTableSeeder extends Seeder
{
    public function run()
    {
        factory(App\Models\Group::class, 1000)->create();
    }
}