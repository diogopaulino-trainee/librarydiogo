<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('publishers', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->default(1)->change();
        });
    }

    public function down()
    {
        Schema::table('publishers', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->change();
        });
    }
};
