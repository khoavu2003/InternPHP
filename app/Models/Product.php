<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;
    protected $casts = [
        'product_id' => 'string',
    ];
    protected $table = 'mst_product'; // không cần nếu theo đúng chuẩn

    protected $primaryKey = 'product_id'; 
    public $timestamps = true; // false nếu không có created_at, updated_at

    protected $fillable = [
        'product_id',
        'product_name',
        'product_image',
        'product_price',
        'is_sales',
        'description',
        'created_at',
        'updated_at',
    ];
}
