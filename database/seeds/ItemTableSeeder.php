<?php

use Illuminate\Database\Seeder;

class ItemTableSeeder extends Seeder
{
    public function run()
    {
        factory(App\Models\Item::class, 5000)->create();
    }
}