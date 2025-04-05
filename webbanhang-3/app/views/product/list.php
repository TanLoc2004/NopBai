<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-4">
    <h1 class="text-center mb-4">Danh sách Sản phẩm</h1>
    <div class="mb-3 text-right">
        <a href="/Product/add" class="btn btn-success">Thêm sản phẩm mới</a>
    </div>

    <div class="alert alert-info" id="loading-alert" style="display:none;">
        Đang tải sản phẩm...
    </div>

    <ul class="list-group" id="product-list">
        <!-- Danh sách sản phẩm sẽ được tải từ API và hiển thị tại đây -->
    </ul>
</div>

<?php include 'app/views/shares/footer.php'; ?>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const productList = document.getElementById('product-list');
    const loadingAlert = document.getElementById('loading-alert');

    // Hiển thị thông báo "Đang tải"
    loadingAlert.style.display = 'block';

    fetch('/api/product')
        .then(response => response.json())
        .then(data => {
            loadingAlert.style.display = 'none';  // Ẩn thông báo tải
            if (data.length === 0) {
                productList.innerHTML = '<li class="list-group-item text-center">Không có sản phẩm nào</li>';
            } else {
                data.forEach(product => {
                    const productItem = document.createElement('li');
                    productItem.className = 'list-group-item d-flex justify-content-between align-items-center';
                    productItem.innerHTML = `
                        <div>
                            <h5><a href="/Product/show/${product.id}">${product.name}</a></h5>
                            <p class="mb-1">${product.description}</p>
                            <p><strong>Giá: </strong>${product.price} VND</p>
                            <p><strong>Danh mục: </strong>${product.category_name}</p>
                        </div>
                        <div>
                            <a href="/Product/edit/${product.id}" class="btn btn-warning btn-sm">Sửa</a>
                            <button class="btn btn-danger btn-sm ml-2" onclick="deleteProduct(${product.id})">Xóa</button>
                        </div>
                    `;
                    productList.appendChild(productItem);
                });
            }
        })
        .catch(error => {
            loadingAlert.style.display = 'none';
            productList.innerHTML = '<li class="list-group-item text-center text-danger">Có lỗi xảy ra khi tải sản phẩm</li>';
        });
});

function deleteProduct(id) {
    if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
        fetch(`/api/product/${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Product deleted successfully') {
                alert('Sản phẩm đã được xóa');
                location.reload();  // Tải lại trang sau khi xóa sản phẩm
            } else {
                alert('Xóa sản phẩm thất bại');
            }
        })
        .catch(error => {
            alert('Có lỗi xảy ra khi xóa sản phẩm');
        });
    }
}
</script>
