<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MemberCards extends Model
{
    protected $table = 'member_cards';

    // Delete status.
    const DELETE = 1;
}
