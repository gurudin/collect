<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardGroup extends Model
{
    protected $table = 'card_group';
    public $timestamps = true;

    protected static function attributeLabels()
    {
        return [
            'name'        => '',
            'number'      => '',
            'description' => '',
            'cover'       => '',
            'status'      => 0,
            'extend'      => null,
        ];
    }
}
