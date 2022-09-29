<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueueProduct extends Model
{
    use HasFactory;
    public $table = "queue_product";
    protected $fillable = [
        'product_id','status'
      ];


}
