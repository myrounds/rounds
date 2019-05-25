<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->json('config');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('categories')->insert([
            [
                'name' => 'delivery',
                'config' => json_encode([
                    'group' => 'Company',
                    'task' => 'Pickup',
                    'assignee' => 'Driver'
                ])
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}
