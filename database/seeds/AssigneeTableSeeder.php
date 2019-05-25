<?php

use Illuminate\Database\Seeder;

class AssigneeTableSeeder extends Seeder
{
    public function run()
    {
        factory(App\Models\Assignee::class, 100)->create();
    }
}