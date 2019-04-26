<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $table = 'store';
    public $timestamps = true;

    /**
     * 物品类型
     * 1:卡片
     * 2:碎片
     */
    const TYPE = [
        ['code' => 1, 'title' => '卡片'],
        ['code' => 2, 'title' => '碎片']
    ];
    const CARD = 1;
    const DEBRIS = 2;

    protected static function attributeLabels()
    {
        return [
            'fk_member_id'     => 0,
            'title'            => '',
            'remark'           => '',
            'exchange'         => 1,
            'exchange_number'  => 0,
            'exchange_crad_id' => '',
            'swop'             => 2,
            'swop_number'      => 0,
            'swop_crad_id'     => '',
            'status'           => 0,
        ];
    }
}
