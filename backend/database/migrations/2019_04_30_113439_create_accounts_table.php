<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            // 所有收入流水相加 = 用户余额
            $table->increments('id');
            $table->integer('fk_member_id')->nullable(false)->comment('商品所属用户 0:官方售卖');
            $table->integer('income')->nullable(false)->comment('碎片数量 正数:收入 负数:支出');
            $table->integer('balance')->nullable(false)->comment('交易后余额');
            $table->string('remark', 100)->nullable(false)->comment('描述');
            $table->timestamp('created_at')->nullable(false);

            $table->index(['fk_member_id']);
            $table->index(['fk_member_id', 'income']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
