<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set('memory_limit', '-1');

        $this->call(AccountTableSeeder::class);
        $this->call(MemberTableSeeder::class);
        $this->call(TaskTableSeeder::class);
        $this->call(ItemTableSeeder::class);
    }

}
