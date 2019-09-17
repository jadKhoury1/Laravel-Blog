<?php

use App\Role;
use Illuminate\Database\Migrations\Migration;

class AddRolesRecords extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Role::query()->create(['id' => 1, 'key' => 'admin', 'name' => 'Admin', 'level' => 1]);
        Role::query()->create(['id' => 2, 'key' => 'customer', 'name' => 'Customer', 'level' => 10]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $keys = ['admin', 'customer'];
        Role::query()->whereIn('key', $keys)->delete();
    }
}
