<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $table = "billing";
    protected $fillable = [
        'no_nota', 'pelanggan', 'subtotal','bayar','status'
    ];
}
