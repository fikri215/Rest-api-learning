<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    protected $fillable = ['user_id', 'balance', 'currency'];

    protected $hidden = ['id', 'user_id', 'created_at', 'updated_at'];
}
