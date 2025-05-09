<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Services\UserService;
use Exception;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    protected $userService;
    protected $userRepository;
    // public function __construct(UserService $userService)
    // {
    //     $this->userService = $userService;
    // }
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function showUserManager()
    {
        return view('user.userManager');
    }
    public function getUsers()
    {
        $users = User::where('is_delete', 0)->paginate(10);
        return response()->json([
            'status' => 'success',
            'userList' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ]
        ]);
    }
    public function addUsers(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'regex:/^[a-zA-Z0-9\s@._\-ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠ-ỹ]*$/u'],
            'email' => ['required', 'regex:/^[a-zA-Z0-9\s@._\-]*$/u'],
            'group_role' => ['required', 'string'],
            'is_active' => ['required', 'boolean'],
            'password' => ['required', 'string', 'min:6']
        ], [
            'name.regex' => 'Tên không được chứa ký tự đặc biệt.',
            'name.required' => 'Tên không được để trống',
            'email.required' => 'email không được để trống',
            'email.regex' => 'Email không được chứa ký tự đặc biệt.',
            'password.required' => 'Mật khẩu là bắt buộc.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.'
        ]);
        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422, [], JSON_UNESCAPED_UNICODE);
        }
        if ($this->userRepository->isEmailExists($validator->validated()['email'])) {
            return response()->json([
                'status' => 'error',
                'message' => 'Email đã tồn tại. Vui lòng nhập email khác.'
            ], 409, [], JSON_UNESCAPED_UNICODE);
        }
        $data = $validator->validated();
        $createUser = [
            'name' => $data['name'],
            'email' => $data['email'],
            'group_role' => $data['group_role'],
            'is_active' => $data['is_active'],
            'password' => Hash::make($data['password']),
            'is_delete' => 0,
        ];
        $user = $this->userRepository->create($createUser);

        return response()->json([
            'status' => 'success',
            'message' => 'Người dùng đã được thêm thành công.',
            'user' => $user
        ]);
    }
    public function updateUser(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'regex:/^[a-zA-Z0-9\s@._\-ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠ-ỹ]*$/u'],
            'email' => ['required', 'regex:/^[a-zA-Z0-9\s@._\-]*$/u'],
            'group_role' => ['required', 'string'],
            'is_active' => ['required', 'boolean'],
            'password' => ['nullable', 'string', 'min:6']
        ], [
            'name.regex' => 'Tên không được chứa ký tự đặc biệt.',
            'email.regex' => 'Email không được chứa ký tự đặc biệt.',
            'name.required' => 'Tên không được để trống',
            'email.required' => 'email không được để trống',
            'password.min' => 'Mật khẩu tối thiểu 6 kí tự'
        ]);
        $currentUser = $this->userRepository->find($id);
        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422, [], JSON_UNESCAPED_UNICODE);
        }
        if ($currentUser && $currentUser->email !== $validator->validated()['email']) {
            if ($this->userRepository->isEmailExists($validator->validated()['email'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Email đã tồn tại. Vui lòng nhập email khác.'
                ], 409, [], JSON_UNESCAPED_UNICODE);
            }
        }
        $data = $validator->validated();
        $user = $this->userRepository->find($id);
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Người dùng không tồn tại.'
            ], 404, [], JSON_UNESCAPED_UNICODE);
        }
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'group_role' => $data['group_role'],
            'is_active' => $data['is_active']
        ];
        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }
        $updateUser = $this->userRepository->update($id, $updateData);
        return response()->json([
            'status' => 'success',
            'message' => 'Người dùng đã được cập nhật thành công.',
            'user' => $updateUser,
        ]);
    }
    public function getUserById($id)
    {
        $data = ['id' => $id];
        $validator = Validator::make($data, [
            'id' => ['required', 'integer'],
        ], [
            'id.required' => 'Id không được để trống',
            'id.integer' => 'Id phải là dạng số',
        ]);

        // Kiểm tra validator
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()->toArray(),
            ], 422, [], JSON_UNESCAPED_UNICODE);
        }
        $user = $this->userRepository->find($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Người dùng không tồn tại.'
            ], 404, [], JSON_UNESCAPED_UNICODE);
        }

        return response()->json([
            'status' => 'success',
            'user' => $user
        ]);
    }
    public function searchUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['nullable', 'regex:/^[a-zA-Z0-9\s@._\-ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠ-ỹ]*$/u'],
            'email' => ['nullable', 'regex:/^[a-zA-Z0-9\s@._\-]*$/u'],
            'group_role' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean']
        ], [
            'name.regex' => 'Tên không được chứa ký tự đặc biệt.',
            'email.regex' => 'Email không được chứa ký tự đặc biệt.',
            'is_active.boolean' => 'Trạng thái không đúng định dạng'
        ]);

        if ($validator->stopOnFirstFailure()->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422, [], JSON_UNESCAPED_UNICODE);
        }

        $users = $this->userRepository->searchUser($validator->validated());

        return response()->json([
            'status' => 'success',
            'userList' => $users->items(),
            'pagination' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ]
        ]);
    }
    public function deleteUser($id)
    {
        $data = ['id' => $id];
        $validator = Validator::make($data, [
            'id' => ['required', 'integer'],
        ], [
            'id.required' => 'Id không được để trống',
            'id.integer' => 'Id phải là dạng số',
        ]);

        // Kiểm tra validator
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()->toArray(),
            ], 422, [], JSON_UNESCAPED_UNICODE);
        }
        try {
            $this->userRepository->delete($id);
            return response()->json([
                'status' => 'Success',
                'message' => 'Xoá người dùng thành công',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Không thể xoá người dùng'
            ]);
        }
    }
    public function deleteMultipleUsers(Request $request)
    {
        $ids = $request->json('user_ids');
        if (!is_array($ids) || empty($ids)) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Danh sách ID không hợp lệ.'
            ], 422);
        }

        try {
            foreach ($ids as $id) {
                $this->userRepository->delete($id); // Giả sử bạn có hàm deleteUser($id)
            }
            return response()->json([
                'status' => 'Success',
                'message' => count($ids) . ' người dùng đã được xoá thành công.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Lỗi khi xoá người dùng: ' . $e->getMessage()
            ], 500);
        }
    }
    public function blockUser($id)
    {
        $data = ['id' => $id];
        $validator = Validator::make($data, [
            'id' => ['required', 'integer'], 
        ], [
            'id.required' => 'Id không được để trống',
            'id.integer' => 'Id phải là dạng số',
        ]);

        // Kiểm tra validator
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()->toArray(),
            ], 422, [], JSON_UNESCAPED_UNICODE);
        }
        $user = User::where('is_delete', 0)->find($id);

        if (!$user) {
            return response()->json(['status' => 'Error', 'message' => 'Không tìm thấy người dùng'], 404);
        }

        $user->is_active = $user->is_active ? 0 : 1;
        $user->save();

        return response()->json([
            'status' => 'Success',
            'message' => 'Trạng thái người dùng đã được cập nhật.',
            'is_active' => $user->is_active
        ]);
    }
}
