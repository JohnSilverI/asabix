<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_tags', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\Post::class ,'post_id')->constrained();
            $table->foreignIdFor(\App\Models\Tag::class ,'tag_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('post_tags', function(Blueprint $table){
            $table->dropForeign('post_tags_post_id_foreign');
            $table->dropForeign('post_tags_tag_id_foreign');
        });

        Schema::dropIfExists('post_tags');
    }
}
