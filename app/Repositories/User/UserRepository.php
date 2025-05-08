<?php

namespace App\Repositories\User;
use App\Repositories\BaseRepository;
use App\Repositories\User\UserRepositoryInterface;
use App\Models\User;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    public function getModel()
    {
        return User::class;
    }
    public function getAll()
    {
        return $this->model->all();
    }
    public function searchUser(array $filters)
    {
        $query = $this->model->query()->where('is_delete', 0);

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (!empty($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        if (!empty($filters['group_role'])) {
            $query->where('group_role', $filters['group_role']);
        }

        // lưu ý: is_active có thể là false, nên dùng isset thay vì empty
        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }
    public function isEmailExists(string $email): bool
    {
        return $this->model->where('email', $email)
            ->where('is_delete', 0)
            ->exists();
    }
    public function find($id){
        $user = $this->model->where('is_delete',0)->find($id);
        return $user;
    }
    public function update($id, $attributes = []){
        $user =$this->model->where('is_delete',0)->find($id);
        if($user){
            $user->update($attributes);
            return $user;
        }
    }
    public function delete($id){
        $user=$this->model->where('is_delete',0)->find($id);
        if($user){
            $user->is_delete=1;
            $user->save();
        }
        return false;
    }
    public function block($id){
        $user = $this->model->where('is_delete',0)->find($id);
        if($user){
            $user->is_active=!$user->is_active;
            $user->save();
        }
    }
}
