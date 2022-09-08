<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFaqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faqs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('article_id')->nullable();
            $table->uuid('user_id')->nullable();
            $table->uuid('category_id')->nullable();
            $table->string('en_title')->index();
            $table->string('bn_title')->index()->nullable();
            $table->text('tag')->nullable();
            $table->string('slug')->unique();
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
        Schema::dropIfExists('faqs');
    }
}
