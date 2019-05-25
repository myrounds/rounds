<?php

use Illuminate\Database\Seeder;

class TaskTableSeeder extends Seeder
{
    public function run()
    {
        factory(App\Models\Task::class, 1000)->create();
    }
}