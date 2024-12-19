<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thống kê Hệ thống</title>
    <!-- Thêm AdminLTE và Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <!-- Thêm DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Header -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Trang chủ</a>
                </li>
            </ul>
        </nav>

        <!-- Sidebar -->
        <!-- <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="#" class="brand-link">
                <span class="brand-text font-weight-light">Thống kê</span>
            </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Bảng điều khiển</p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </aside> -->

        <!-- Content -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <h1 class="m-0 text-center">Thống kê Hệ thống</h1>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- Small Box Tổng số khách hàng -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ $totalCustomers }}</h3>
                                    <p>Tổng số khách hàng</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <a href="#" class="small-box-footer">
                                    Chi tiết <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>

                        <!-- Small Box Tổng doanh thu -->
                        <div class="col-lg-3 col-6">
                            <div class="small-box bg-success">
                                <div class="inner">
                                    <h3>{{ number_format($totalRevenue) }} VNĐ</h3>
                                    <p>Tổng doanh thu</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <a href="#" class="small-box-footer">
                                    Chi tiết <i class="fas fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Khách hàng mới theo tháng -->
                    <div class="card mt-4">
                        <div class="card-header bg-primary text-white">
                            <h3 class="card-title">Khách hàng mới theo tháng</h3>
                        </div>
                        <div class="card-body">
                            <table id="newCustomersTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Năm</th>
                                        <th>Tháng</th>
                                        <th>Số khách hàng mới</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($monthlyNewCustomers as $customer)
                                    <tr>
                                        <td>{{ $customer->year }}</td>
                                        <td>{{ $customer->month }}</td>
                                        <td>{{ $customer->total }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Bảng Sản phẩm bán chạy nhất -->
                    <div class="card mt-4">
                        <div class="card-header bg-warning text-dark">
                            <h3 class="card-title">Sản phẩm bán chạy nhất</h3>
                        </div>
                        <div class="card-body">
                            <table id="topProductsTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Tên sản phẩm</th>
                                        <th>Số lượng bán</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topProducts as $product)
                                    <tr>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->total_sold }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!-- Thống kê khách hàng mua hàng nhiều nhất -->
                    <!-- Thống kê khách hàng mua hàng nhiều nhất -->
                    <div class="card mt-4">
                        <div class="card-header bg-info text-white">
                            <h3 class="card-title">Khách hàng mua hàng nhiều nhất</h3>
                        </div>
                        <div class="card-body">
                            <table id="topCustomersTable" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Tên khách hàng</th>
                                        <th>Số lượng sản phẩm mua</th>
                                        <th>Số điện thoại</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topCustomers as $customer)
                                    <tr>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->total_purchased }}</td>
                                        <td>{{ $customer->phone }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>


                    <!-- Kết nối DataTables và kích hoạt tính năng sắp xếp -->
                    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
                    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
                    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

                    <script>
                        $(document).ready(function() {
                            // Kích hoạt DataTables cho bảng
                            $('#topProductsTable').DataTable({
                                "order": [
                                    [1, 'desc']
                                ], // Sắp xếp theo cột thứ 2 (Số lượng bán) từ cao đến thấp
                                "lengthMenu": [10, 25, 50, 100], // Hiển thị số lượng bản ghi mỗi trang
                                "pageLength": 10, // Mặc định hiển thị 10 bản ghi mỗi trang
                            });
                        });
                    </script>

                    <script>
                        $(document).ready(function() {
                            // Kích hoạt DataTables cho bảng với phân trang, tìm kiếm và sắp xếp
                            $('#topCustomersTable').DataTable({
                                "order": [
                                    [1, 'desc']
                                ], // Sắp xếp theo cột thứ 2 (Số lượng sản phẩm mua) từ cao đến thấp
                                "lengthMenu": [10, 25, 50, 100], // Cho phép người dùng chọn số lượng bản ghi mỗi trang
                                "pageLength": 10, // Mặc định hiển thị 10 bản ghi mỗi trang
                                // "processing": true, // Hiển thị thanh xử lý khi tải dữ liệu
                                // "serverSide": false, // Phân trang ở client
                            });
                        });
                    </script>


</body>

</html>