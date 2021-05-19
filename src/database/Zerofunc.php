<?php
namespace App\Plugins\zero\src\database;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ZeroFunc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('zero_func')) {
            Schema::create('zero_func', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('name');
                $table->integer('tag');
                $table->integer('value');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zero_func');
    }
}
