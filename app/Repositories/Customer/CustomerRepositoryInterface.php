<?php
namespace App\Repositories\Customer;
use App\Repositories\RepositoryInterface;

interface CustomerRepositoryInterface extends RepositoryInterface{
    public function searchCustomer(array $filters);


}