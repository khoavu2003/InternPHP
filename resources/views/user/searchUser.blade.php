
<style>
    /* Styling for the search bar component */
    .search-bar-container {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        margin-left:100px;

    }

    .search-title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 15px;
    }

    .search-inputs {
        display: flex;
        gap: 15px;
        margin-bottom: 15px;
    }

    .search-input,
    .search-select {
        padding: 10px;
        width: 250px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    .search-buttons {
        display: flex;
        gap: 10px;
    }

    .search-buttons .btn {
        padding: 10px 20px;
        border-radius: 5px;
        display: flex;
        align-items: center;
    }

    .search-buttons .btn i {
        margin-right: 5px;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
    }

    .btn-info {
        background-color: #17a2b8;
        color: white;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
    }

    .btn:hover {
        opacity: 0.9;
    }

    #clear-button {
        background-color:  #28A745;
        border: 1px solid #ccc;

    }

</style>
<!-- search-bar.jsp -->
<div class="search-bar-container">
    <h4 class="search-title">Danh Sách Người Dùng</h4>
    <div class="search-inputs">
        <!-- Name Input -->
        <input type="text" id="search-name" placeholder="Nhập họ tên" class="search-input" />

        <!-- Email Input -->
        <input type="email" id="search-email" placeholder="Nhập email" class="search-input" />

       <!-- Group Dropdown -->
      <select id="search-group" class="search-select">
                  <option value="">Chọn nhóm</option>
                  <!-- Add default group roles -->
                  <option value="admin">Admin</option>
                  <option value="User">User</option>
              </select>

              <!-- Status Dropdown -->
              <select id="search-status" class="search-select">
                  <option value="">Chọn trạng thái</option>
                  <!-- Add default status options -->
                  <option value="Đang Hoạt Động">Đang Hoạt Động</option>
                  <option value="Tạm Khoá">Tạm Khoá</option>
              </select>
    </div>

    <div class="search-buttons">
        <button class="btn btn-primary" id="add-new-button">
            <i class="bi bi-person-plus"></i> Thêm mới
        </button>
        <button class="btn btn-primary" id="delete-selected">
                     <i class="bi bi-person-plus"></i> Xoá các mục chọn
                 </button>

        <button class="btn btn-primary" id="search-button" style="margin-left:648px;font-weight:Bold">
            <i class="bi bi-search"></i> Tìm kiếm
        </button>

        <button class="btn btn-danger" id="clear-button" style="font-weight:Bold">
            <i class="bi bi-x-circle" ></i> Xóa tìm
        </button>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
</script>