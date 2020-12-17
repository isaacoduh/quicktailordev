<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = ['payment_ref', 'amount', 'status', 'rider_id', 'date_delivered', 'items', 'user_id', 'address'];

    protected function users()
    {
        return $this->belongsTo(User::class);
    }
}
