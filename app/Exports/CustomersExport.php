<?php

namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomersExport implements FromCollection,WithHeadings
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }
    public function collection()
    {
        $query = Customer::query();

        if (!empty($this->filters['name'])) {
            $query->where('customer_name', 'like', '%' . $this->filters['name'] . '%');
        }
        if (!empty($this->filters['email'])) {
            $query->where('email', 'like', '%' . $this->filters['email'] . '%');
        }
        if (!empty($this->filters['address'])) {
            $query->where('address', 'like', '%' . $this->filters['address'] . '%');
        }
        if ($this->filters['is_active'] !== '' && $this->filters['is_active'] !== null) {
            $query->where('is_active', $this->filters['is_active']);
        }
        $query->orderBy('created_at', 'desc');
        // Phân trang: giả sử bạn dùng 10 bản ghi mỗi trang
        $perPage = 10;
        $page = max(1, (int) $this->filters['page']);
        $query->skip(($page - 1) * $perPage)->take($perPage);

        return $query->select(
            'customer_name',
            'email',
            'tel_num',
            'address',
            'customer_id'
        )->get();
    }

    public function headings(): array
    {
        return [
            'customer_name',
            'email',
            'tel_num',
            'address',
            'customer_id',
        ];
    }
}
