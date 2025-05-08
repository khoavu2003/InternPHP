<?php

namespace App\Services;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;

class CustomerService
{
    public function searchCustomers(array $filters)
    {
        $query = Customer::query();

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
    public function getCustomerByID($id){
        return Customer::find($id);
    }
    public function createCustomer(array $data)
    {
        return Customer::create([
            'customer_name' => $data['customer_name'],
            'email' => $data['email'],
            'address' => $data['address'] ?? null,
            'tel_num'=>$data['tel_num']??null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }
    public function updateCustomer($id,array $data){
        $customer=Customer::findOrFail($id);
        $customer->customer_name = $data['customer_name']??$customer->customer_name;
        $customer->email=$data['email']??$customer->email;
        $customer->tel_num=$data['tel_num']??$customer->tel_num;
        $customer->address=$data['address']??$customer->address;
        
        $customer->save();
        return $customer;
    }
}
