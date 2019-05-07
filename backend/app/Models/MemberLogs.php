<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberLogs extends Model
{
    protected $table = 'member_logs';
    public $timestamps = false;

    const TYPE_DRAW = 1;
    const TYPE_SHARE = 2;
    const TYPE_AD = 3;
    const TYPE_DESTRUCT = 4;
    const TYPE_GAIN = 5;
    const TYPE_EXCHANGE = 6;
    const TYPE_LOGIN = 7;
}
