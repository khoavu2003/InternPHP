<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{

    use HasFactory;
    protected $table = 'mst_customer'; // không cần nếu theo đúng chuẩn

    protected $primaryKey = 'customer_id'; 
    public $timestamps = true; // false nếu không có created_at, updated_at

    protected $fillable = [
        'customer_id',
        'customer_name',
        'email',
        'tel_num',
        'address',
        'is_active',
        'is_delete',
        'created_at',
        'updated_at',
    ];
}
