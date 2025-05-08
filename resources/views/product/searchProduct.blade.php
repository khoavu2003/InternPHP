<style>
    /* Styling for the search bar component */
    .search-bar-container {
        background-color: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        margin-left: 100px;
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
        background-color: #28A745;
        border: 1px solid #ccc;

    }

    .price-separator {
        display: inline-block;
        padding: 0 10px;
        /* Space around the tilde */
        font-size: 18px;
        /* Adjust font size */
        line-height: 38px;
        /* Align it vertically with the inputs */
        font-weight: bold;
        color: #007bff;
        /* Optional color */
    }
</style>

<!-- search-bar.jsp -->
<div class="search-bar-container">
    <h4 class="search-title">Danh sách sản phẩm</h4>
    <div class="search-inputs">
        <!-- Product Name Input -->
        <input type="text" id="search-name" placeholder="Nhập tên sản phẩm" class="search-input" />

        <!-- Product Sale Status Input -->
        <select id="search-status" class="search-select">
            <option value="">Chọn tình trạng</option>
            <option value="1">Đang Bán</option>
            <option value="0">Ngừng Bán</option>
        </select>

        <!-- Min Price Input -->
        <input type="number" id="search-min-price" placeholder="Giá tối thiểu" class="search-input" />
        <span class="price-separator">~</span>
        <!-- Max Price Input -->
        <input type="number" id="search-max-price" placeholder="Giá tối đa" class="search-input" />
    </div>

    <div class="search-buttons">
        <button class="btn btn-primary" id="add-new-product-button">
            <i class="bi bi-person-plus"></i> Thêm mới
        </button>

        <form id="excelUploadForm" action="uploadExcel" method="post" enctype="multipart/form-data"
            style="display: none;">
            <input type="file" id="excel-file" name="excelFile" accept=".xlsx, .xls" />
        </form>

        <button class="btn btn-primary" id="search-button" style=" margin-left: 780px;font-weight:Bold">
            <i class="bi bi-search"></i> Tìm kiếm
        </button>
        <button class="btn btn-danger" id="clear-button" style="font-weight:Bold">
            <i class="bi bi-x-circle"></i> Xóa tìm
        </button>
    </div>
</div>