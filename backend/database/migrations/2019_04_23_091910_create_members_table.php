<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->string('opienid')->nullable(false);
            $table->string('unionid')->nullable(false);
            $table->string('nick_name')->comment('用户昵称');
            $table->tinyInteger('gender')->default(0)->nullable(false)->comment('性别 0：未知、1：男、2：女');
            $table->string('avatar')->comment('头像');
            $table->string('country', 100)->comment('地区');
            $table->string('province', 100)->comment('省');
            $table->string('city', 100)->comment('市');
            $table->string('platform', 50)->default('wechat')->nullable(false)->comment('平台 wechat:微信');
            $table->integer('total_draw')->nullable(false)->comment('单日最大抽奖次数');
            $table->integer('base_draw')->nullable(false)->comment('基本抽奖次数');
            $table->tinyInteger('status')->default(1)->nullable(false)->comment('状态 0:禁止使用 1:正常');
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
        Schema::dropIfExists('members');
    }
}
