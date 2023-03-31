<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $guarded = [];

    protected $hidden = ['id', 'wallet_id', 'created_at', 'updated_at'];
}
