<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->nullable();
            $table->uuid('category_id');
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->string('en_title')->index();
            $table->string('bn_title')->index()->nullable();
            $table->text('tag')->nullable();
            $table->string('slug');
            $table->text('en_short_summary')->nullable();
            $table->text('bn_short_summary')->nullable();
            $table->tinyInteger('commentable_status')->comment('0 => Inactive, 1 => Active');
            $table->enum('status', ['draft', 'hide', 'private', 'public']);
            $table->timestamp('publish_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
