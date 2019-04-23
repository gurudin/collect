<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_group', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100)->nullable(false)->comment('名称');
            $table->integer('number')->nullable(false)->comment('总数');
            $table->text('description')->nullable(true)->comment('描述');
            $table->string('cover')->nullable(true)->comment('封面图片');
            $table->tinyInteger('status')->default(0)->comment('0:下线 1:上线');
            $table->text('extend')->nullable(true)->comment('扩展字段');
            $table->timestamps();

            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_group');
    }
}
