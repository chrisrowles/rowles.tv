<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetadataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metadata', function (Blueprint $table) {
            $table->unsignedBigInteger('video_id');
            $table->unsignedBigInteger('filesize')->nullable();
            $table->string('format', 255)->nullable();
            $table->string('codec', 255)->nullable();
            $table->unsignedBigInteger('bitrate')->nullable();
            $table->double('duration', 16, 8)->nullable();
            $table->string('thumbnail_filepath', 255)->nullable();
            $table->string('thumbnail_filename', 255)->nullable();
            $table->string('preview_filepath', 255)->nullable();
            $table->string('preview_filename', 255)->nullable();

            $table->foreign('video_id')->references('id')->on('videos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metadata');
    }
}
