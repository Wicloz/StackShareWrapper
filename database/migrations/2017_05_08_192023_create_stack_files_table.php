<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStackFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stack_files', function (Blueprint $table) {
            $table->increments('id');

            $table->string('path', 4096)->unique();
            $table->string('path_hash')->unique();

            $table->bigInteger('size')->unsinged();
            $table->string('mimetype_remote');

            $table->integer('parent_id')->unsinged();
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
        Schema::dropIfExists('stack_files');
    }
}
