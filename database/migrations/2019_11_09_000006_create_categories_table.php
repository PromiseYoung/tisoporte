<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');

            $table->string('color')->nullable();
            // llave foranea de users
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            $table->timestamps();

            $table->softDeletes();
        });
    }
    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
}
