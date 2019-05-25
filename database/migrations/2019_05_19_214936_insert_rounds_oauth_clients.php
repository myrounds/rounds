<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class InsertRoundsOauthClients extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('oauth_clients')->insert([
            [
                'name' => 'Rounds Personal Access Client',
                'secret' => 'UE2g5dObkaT5MgOlzuEVDKZIOL1igOr8GgPYTvX3',
                'redirect' => 'http://localhost',
                'personal_access_client' => 1,
                'password_client' => 0,
                'revoked' => 0,
            ],
            [
                'name' => 'Rounds Password Grant Client',
                'secret' => 'Gi7pyS9EqCTkTMJ6UN7gYElu2mjwISode1SYUO0V',
                'redirect' => 'http://localhost',
                'personal_access_client' => 0,
                'password_client' => 1,
                'revoked' => 0,
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
        DB::table('oauth_clients')
            ->where('name', 'Rounds Personal Access Client')
            ->orWhere('name', 'Rounds Password Grant Client')
            ->delete();
    }
}
