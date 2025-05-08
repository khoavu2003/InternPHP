<!-- Modal Add User -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addUserForm">
                <div class="text-danger text-center" id="addMessage" style="display:none;"></div>
                <div class="modal-header">
                    <h5 class="modal-title" id="addUserModalLabel">Thêm Người Dùng Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <!-- Các trường nhập liệu của người dùng -->
                    <div class="mb-3">
                        <label for="add-name" class="form-label">Họ tên</label>
                        <input type="text" class="form-control" id="add-name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="add-email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="add-email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="add-password" class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" id="add-password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="add-confirm-password" class="form-label">Xác nhận mật khẩu</label>
                        <input type="password" class="form-control" id="add-confirm-password" name="confirmPassword" required>
                    </div>
                    <div class="mb-3">
                        <label for="add-group" class="form-label">Nhóm</label>
                        <select class="form-select" id="add-group" name="groupRole" required>
                            <option value="admin">Admin</option>
                            <option value="User">User</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="add-status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="add-status" name="isActive" required>
                            <option value="Đang Hoạt Động">Đang Hoạt Động</option>
                            <option value="Tạm Khoá">Tạm Khoá</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="saveUserBtn">Lưu</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>