<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\CustomerService;
use App\Models\Customer;
use App\Exports\CustomersExport;
use App\Imports\CustomersImport;
use App\Repositories\Customer\CustomerRepositoryInterface;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    protected $customerRepository;
    protected $customerService;
    // public function __construct(CustomerService $customerService)
    // {
    //     $this->customerService = $customerService;
    // }
    public function __construct(CustomerRepositoryInterface $customerRepository){
        $this->customerRepository = $customerRepository;
    }
    public function showCustomerManager()
    {
        return view('customer.customer');
    }
    public function importExcel(Request $request)
    {
        $request->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls'
        ]);

        try {
            Excel::import(new CustomersImport, $request->file('excelFile'));
            return response()->json(['status' => 'success', 'message' => 'Import thành công']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Lỗi: ' . $e->getMessage()], 500);
        }
    }
    public function exportExcel(Request $request)
    {
        $filters = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'address' => $request->input('address'),
            'is_active' => $request->input('isActive'),
            'page' => $request->input('currentPage', 1),
        ];
    
        return Excel::download(new CustomersExport($filters), 'danh_sach_khach_hang.xlsx');
    }
    public function searchCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => ['nullable', 'string', 'max:255', 'regex:/^[\p{L}0-9\s._@\-ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠ-ỹ]+$/u'],

            'email' => ['nullable', 'regex:/^[a-zA-Z0-9\s@._\-]*$/u'],
            'is_active' => ['nullable', 'boolean'],
            'address' => ['nullable', 'string', 'max:255']
            
        ], [
            'customer_name.regex' => 'Tên khách hàng không được chứa ký tự đặc biệt.',
            'email.regex' => 'Email không được chứa ký tự đặc biệt.',
            'address.string' => 'Địa chỉ phải là chuỗi văn bản.'
        ]);

        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422, [], JSON_UNESCAPED_UNICODE);
        }

        $customers = $this->customerRepository->searchCustomer($validator->validated());

        return response()->json([
            'status' => 'success',
            'customerList' => $customers->items(),
            'pagination' => [
                'current_page' => $customers->currentPage(),
                'last_page' => $customers->lastPage(),
                'per_page' => $customers->perPage(),
                'total' => $customers->total(),
            ]
        ]);
    }
    public function addCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}0-9\s._@\-ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠ-ỹ]+$/u'],

            'email' => ['required', 'email', 'max:255'],

            'tel_num' => ['required', 'regex:/^[0-9+\s\-()]{9,11}$/'],

            'address' => ['required', 'string', 'max:255']
        ], [
            'customer_name.regex' => 'Tên khách hàng không được chứa ký tự đặc biệt.',
            'email.email' => 'Email không hợp lệ.',
            'customer_name.required' => 'Tên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'tel_num.required' => 'Số điện thoại không được để trống.',
            'address.required' => 'Địa chỉ không được để trống.',
            'tel_num.regex' => 'Số điện thoại chỉ được chứa số, dấu cộng, dấu gạch ngang, khoảng trắng, từ 9-11 số.',
            'address.string' => 'Địa chỉ phải là chuỗi văn bản.'
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422, [], JSON_UNESCAPED_UNICODE);
        }
        $data=$validator->validated();
        $createData=[
            'customer_name' => $data['customer_name'],
            'email' => $data['email'],
            'address' => $data['address'] ?? null,
            'tel_num'=>$data['tel_num']??null,
            'is_active' => $data['is_active'] ?? true,
        ];
        $user = $this->customerRepository->create($createData);

        return response()->json([
            'status' => 'success',
            'message' => 'Khách hàng đã được thêm thành công.',
            'user' => $user
        ]);
    }
    
    public function updateCustomer(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => ['required', 'string', 'max:255', 'regex:/^[\p{L}0-9\s._@\-ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠ-ỹ]+$/u'],
            'email' => ['required', 'email', 'max:255'],
            'tel_num' => ['required', 'regex:/^[0-9+\s\-()]{9,11}$/'],
            'address' => ['required', 'string', 'max:255']
        ], [
            'customer_name.regex' => 'Tên khách hàng không được chứa ký tự đặc biệt.',
            'email.email' => 'Email không hợp lệ.',
            'customer_name.required' => 'Tên không được để trống.',
            'email.required' => 'Email không được để trống.',
            'tel_num.required' => 'Số điện thoại không được để trống.',
            'address.required' => 'Địa chỉ không được để trống.',
            'tel_num.regex' => 'Số điện thoại chỉ được chứa số, dấu cộng, dấu gạch ngang, khoảng trắng, từ 9-11 số.',
            'address.string' => 'Địa chỉ phải là chuỗi văn bản.'
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422, [], JSON_UNESCAPED_UNICODE);
        }
        $data = $validator->validated();
        $customer = $this->customerRepository->find($id);
        if (!$customer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Người dùng không tồn tại.'
            ], 404, [], JSON_UNESCAPED_UNICODE);
        }
        $updateData=[
            'customer_name' => $data['customer_name'],
            'email' => $data['email'],
            'address' => $data['address'] ?? null,
            'tel_num'=>$data['tel_num']??null,
        ];
        $user = $this->customerRepository->update($id,   $updateData);
        return response()->json([
            'status' => 'success',
            'message' => 'Khách hàng đã được cập nhật thành công.',
            'user' => $user,
        ]);
    }
}
