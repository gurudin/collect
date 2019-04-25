<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk_member_id')->nullable(false)->comment('商品所属用户 0:官方售卖');
            $table->string('title', 50)->nullable(false)->comment('交易名称');
            $table->string('remark', 100)->comment('简介');
            /**
             * 物品类型
             * 1:卡片
             * 2:碎片
             */
            $table->tinyInteger('exchange')->nullable(false)->comment('交换物品类型');
            $table->integer('exchange_number')->nullable(false)->comment('交换物品数量');
            $table->tinyInteger('swop')->nullable(false)->comment('需要交换物品类型');
            $table->tinyInteger('swop_number')->nullable(false)->comment('需要交换物品类型');
            $table->tinyInteger('status')->default(0)->nullable(false)->comment('状态 0:下架 1:上架 2:交易成功');
            $table->integer('buyer_id')->comment('购买人id');
            $table->timestamps();

            $table->index(['created_at']);
            $table->index(['buyer_id']);
            $table->index(['status']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store');
    }
}
