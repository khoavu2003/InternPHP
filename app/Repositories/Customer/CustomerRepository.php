<?php
namespace App\Repositories\Customer;
use App\Repositories\BaseRepository;
use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Models\Customer;
class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface{
    public function getModel(){
        Return Customer::class;
    }
    public function getAll(){

    }
    public function searchCustomer($filters){
        $query = $this->model->query();

        if (!empty($filters['customer_name'])) {
            $query->where('customer_name', 'like', '%' . $filters['customer_name'] . '%');
        }

        if (!empty($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        if (!empty($filters['address'])) {
            $query->where('address', $filters['address']);
        }
        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }
        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function update($id,$attributes=[]){
        $customer= $this->find($id);
        if($customer){
            $customer->update($attributes);
            return $customer;
        }
        return false;
    }
}