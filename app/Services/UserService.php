<?php

namespace App\Services;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Models\User;

class UserService
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }
    public function createUser(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'group_role' => $data['group_role'] ?? null,
            'is_active' => $data['is_active'] ?? true,
            'password' => Hash::make($data['password']),
            'is_delete' => 0
        ]);
    }
    public function isEmailExists(string $email): bool
    {
        return User::where('email', $email)
                   ->where('is_delete', 0)
                   ->exists();
    }
    public function updateUser($id,array $data){
        $user=User::where('is_delete',0)->findOrFail($id);
        $user->name = $data['name']??$user->name;
        $user->email=$data['email']??$user->email;
        $user->group_role=$data['group_role']??$user->group_role;
        $user->is_active=$data['is_active']??$user->is_active;
        if(!empty($data['password'])){
            $user->password = Hash::make($data['password']);
        }
        $user->save();
        return $user;
    }
    public function searchUsers(array $filters)
    {
        $this->productRepository->searchProduct($filters);
    }
    public function getUserByID($id){
        return User::where('is_delete',0)->find($id);
    }
    public function deleteUserByID($id){
        $user= User::where('is_delete',0)->find($id);
        $user->is_delete=1;
        $user->save();
    }
    public function blockUser($id){
        $user= User::where('is_delete',0)->find($id);
        $user->is_active ?0:1;
        $user->save();
    }
}
