<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpiderRule extends Model
{
    protected $table = 'spider_rule';
    public $timestamps = true;

    protected static function attributeLabels()
    {
        return [
            'parent_id'  => 0,
            'name'       => '',
            'url'        => '',
            'type'       => 1,
            'slice'      => '',
            'rule'       => null,
            'filed_rule' => null,
            'enable'     => 0,
        ];
    }
}
