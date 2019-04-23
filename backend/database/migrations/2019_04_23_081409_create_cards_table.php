<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_group_id')->nullable(false)->comment('card_group 外键');
            $table->integer('total_cards')->default(0)->nullable(false)->comment('发放卡总数');
            $table->integer('issued')->default(0)->nullable(false)->comment('已发放卡总数');
            $table->float('chance', 6, 4)->nullable(false)->comment('获取几率 百分比');
            /**
             * 获取难度级别
             * 数字越大越困难
             * 算法: (total_cards - issued) > 0 && (Total number of user cards >= difficulty_level) && chance
             */
            $table->integer('difficulty_level')->default(0)->nullable(false)->comment('获取难度');
            $table->string('name', 100)->nullable(false)->comment('名称');
            $table->text('description')->nullable(true)->comment('描述');
            $table->string('cover')->nullable(true)->comment('封面图片');
            $table->tinyInteger('status')->default(0)->comment('0:下线 1:上线');
            $table->text('extend')->nullable(true)->comment('扩展字段');
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
        Schema::dropIfExists('cards');
    }
}
