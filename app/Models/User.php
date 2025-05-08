<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class User extends Authenticatable
{
    use HasFactory;
    protected $table = 'mst_users'; // không cần nếu theo đúng chuẩn

    protected $primaryKey = 'id'; // mặc định là 'id', chỉ cần nếu khác

    public $timestamps = true; // false nếu không có created_at, updated_at

    protected $fillable = [
        'name',
        'email',
        'password',
        'remember_token',
        'verify_email',
        'is_active',
        'is_delete',
        'group_role',
        'last_login_at',
        'last_login_ip',
        'created_at',
        'updated_at',
    ];
}
