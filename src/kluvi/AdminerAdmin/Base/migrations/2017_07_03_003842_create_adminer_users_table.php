<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminerUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adminer_users', function (Blueprint $table) {
            $table->string('login', 100)->comment('{"name": "Login"}');
            $table->string('password', 255)->comment('{"name": "Password", "type": "password"}');
            $table->dateTime('updated_at');
            $table->dateTime('created_at');
            $table->primary('login');
        });

        DB::statement("ALTER TABLE `adminer_users` comment '{\"name\": \"Administrators\"}'");

        DB::unprepared('
        CREATE TRIGGER `users_bi` BEFORE INSERT ON `adminer_users` FOR EACH ROW
            BEGIN
                SET NEW.created_at = NOW(), NEW.updated_at = NOW();
            END
        ');

        DB::unprepared('
        CREATE TRIGGER `users_bu` BEFORE UPDATE ON `adminer_users` FOR EACH ROW
            BEGIN
                SET NEW.updated_at=NOW();
            END
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adminer_users');
    }
}
