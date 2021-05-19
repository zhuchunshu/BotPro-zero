<?php
namespace App\Plugins\zero\src\database;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Zerouser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('zero_user')) {
            Schema::create('zero_user', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('qq');
                $table->integer('group');
                $table->integer('jifen')->comment('积分');
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
        Schema::dropIfExists('zero_user');
    }
}
