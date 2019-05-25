<?php

use Illuminate\Database\Seeder;

class AccountTableSeeder extends Seeder
{
    public function run()
    {
        factory(App\Models\Account::class, 5)->create();
    }
}