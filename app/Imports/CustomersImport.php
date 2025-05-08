<?php
namespace App\Imports;

use App\Models\Customer;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;

class CustomersImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Bỏ qua dòng trống
        if (!$row['customer_name'] || !$row['email']) {
            return null;
        }

        // Validation thủ công
        $validator = Validator::make($row, [
            'customer_name' => ['required', 'regex:/^[a-zA-Z0-9\sàáảãạăắằẳẵặâấầẩẫậèéẻẽẹêếềểễệìíỉĩịòóỏõọôốồổỗộơớờởỡợùúủũụưứừửữựýỳỷỹỵđĐ\-]+$/u'],
            'email' => ['required', 'email'],
            'tel_num' => ['nullable', 'regex:/^[0-9]+$/'],
            'address' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return null;
        }

        // Nếu có ID => cập nhật, ngược lại tạo mới
        $existing = Customer::find($row['customer_id'] ?? null);

        if ($existing) {
            $existing->update([
                'customer_name' => $row['customer_name'],
                'email' => $row['email'],
                'tel_num' => $row['tel_num'],
                'address' => $row['address'],
                'is_active' => true
            ]);
            return null; // không cần tạo mới
        }

        return new Customer([
            'customer_name' => $row['customer_name'],
            'email' => $row['email'],
            'tel_num' => $row['tel_num'],
            'address' => $row['address'],
            'is_active' => true
        ]);
    }
}
