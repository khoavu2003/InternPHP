<?php
namespace App\Repositories\User;

use App\Repositories\BaseRepository;
use App\Repositories\RepositoryInterface;

interface UserRepositoryInterface extends RepositoryInterface{
    public function searchUser(array $filters);

    public function update($id,$attributes=[]);

    public function isEmailExists(string $email);
}