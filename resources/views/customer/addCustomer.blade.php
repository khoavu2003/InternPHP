
<!-- Modal Add Customer -->
<div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addCustomerForm">
                <div class="text-danger text-center" id="addCustomerMessage" style="display:none;"></div>
                <div class="modal-header">
                    <h5 class="modal-title" id="addCustomerModalLabel">Thêm Khách Hàng Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="customer-name" class="form-label">Họ tên</label>
                        <input type="text" class="form-control" id="customer-name" name="customerName" required>
                    </div>
                    <div class="mb-3">
                        <label for="customer-email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="customer-email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="customer-phone" class="form-label">Số điện thoại</label>
                        <input type="text" class="form-control" id="customer-phone" name="telNum" required>
                    </div>
                    <div class="mb-3">
                        <label for="customer-address" class="form-label">Địa chỉ</label>
                        <input type="text" class="form-control" id="customer-address" name="address" required>
                    </div>
                    <div class="mb-3">
                        <label for="customer-status" class="form-label">Trạng thái</label>
                        <select class="form-select" id="customer-status" name="isActive" required>
                            <option value="Đang Hoạt Động">Đang hoạt động</option>
                            <option value="Tạm Khoá">Tạm khoá</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="saveCustomerBtn">Lưu</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>