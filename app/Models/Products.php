<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Products extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $softDelete = true;

    public $table = "products";
    

    protected $fillable = [
        'name', 'price','category','picture'
      ];

}
