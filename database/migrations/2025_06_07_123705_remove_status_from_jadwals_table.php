<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveStatusFromJadwalsTable extends Migration
{
    public function up()
    {
        Schema::table('jadwals', function (Blueprint $table) {
            if (Schema::hasColumn('jadwals', 'status')) {
                $table->dropColumn('status');
            }
        });
    }

    public function down()
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->string('status')->default('tersedia');
        });
    }
}
