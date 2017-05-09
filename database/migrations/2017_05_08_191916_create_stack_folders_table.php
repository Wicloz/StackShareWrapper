<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStackFoldersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stack_folders', function (Blueprint $table) {
            $table->increments('id');

            $table->string('path', 4096)->unique();
            $table->string('path_slug', 4096)->unique();
            $table->string('path_hash')->unique();

            $table->integer('parent_id')->unsinged()->nullable();
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('stack_folders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stack_folders');
    }
}
