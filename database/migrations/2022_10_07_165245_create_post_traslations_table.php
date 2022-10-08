<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTraslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_translations', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Post::class ,'post_id')->constrained();
            $table->foreignIdFor(\App\Models\Language::class ,'language_id')->constrained();
            $table->string('title', 50);
            $table->string('description', 150);
            $table->longText('content');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_translations', function(Blueprint $table){
            $table->dropForeign('post_translations_post_id_foreign');
            $table->dropForeign('post_translations_language_id_foreign');
        });

        Schema::dropIfExists('post_translations');
    }
}
