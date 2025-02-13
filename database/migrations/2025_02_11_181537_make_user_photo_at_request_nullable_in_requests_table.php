<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->string('user_photo_at_request')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->string('user_photo_at_request')->nullable(false)->change();
        });
    }
};
