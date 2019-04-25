<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cards extends Model
{
    protected $table = 'cards';
    public $timestamps = true;

    protected static function attributeLabels()
    {
        return [
            'fk_group_id'      => '',
            'total_cards'      => '',
            'chance'           => '',
            'difficulty_level' => '',
            'name'             => '',
            'description'      => '',
            'cover'            => '',
            'status'           => 0,
            'extend'           => null,
        ];
    }
}
