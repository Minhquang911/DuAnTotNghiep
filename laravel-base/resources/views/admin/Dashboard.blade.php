@extends('layouts.admin.AdminLayout')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tổng quan về tình hình kinh doanh cửa hàng</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Thống kê doanh thu -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Doanh thu (Tháng)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">45.000.000 VNĐ</div>
                                            <div class="text-xs text-success mt-2">
                                                <i class="fas fa-arrow-up"></i> Tăng 12% so với tháng trước
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                
                        <!-- Thống kê doanh thu năm -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Doanh thu (Năm)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">350.000.000 VNĐ</div>
                                            <div class="text-xs text-success mt-2">
                                                <i class="fas fa-arrow-up"></i> Đạt 75% chỉ tiêu năm
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                
                        <!-- Đơn hàng đang xử lý -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Đơn hàng đang xử lý
                                            </div>
                                            <div class="row no-gutters align-items-center">
                                                <div class="col-auto">
                                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">15</div>
                                                </div>
                                                <div class="col">
                                                    <div class="progress progress-sm mr-2">
                                                        <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="text-xs mt-2">
                                                <i class="fas fa-clock"></i> Ước tính hoàn thành: 2 ngày
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                
                        <!-- Số lượng khách hàng -->
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Khách hàng</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">245</div>
                                            <div class="text-xs text-success mt-2">
                                                <i class="fas fa-arrow-up"></i> 18 khách hàng mới trong tháng
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-users fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <!-- Biểu đồ doanh thu -->
                    
                
                    <!-- Bảng đơn hàng mới nhất -->
                    <div class="row">
                        <div class="col-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Đơn hàng mới nhất</h6>
                                    <a href="#" class="btn btn-sm btn-primary shadow-sm">
                                        <i class="fas fa-download fa-sm text-white-50"></i> Xuất báo cáo
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>Mã đơn hàng</th>
                                                    <th>Khách hàng</th>
                                                    <th>Sản phẩm</th>
                                                    <th>Tổng tiền</th>
                                                    <th>Ngày đặt</th>
                                                    <th>Trạng thái</th>
                                                    <th>Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>#ORD-2023001</td>
                                                    <td>Nguyễn Văn A</td>
                                                    <td>Áo thun nam, Quần jean</td>
                                                    <td>850.000 VNĐ</td>
                                                    <td>20/11/2023</td>
                                                    <td><span class="badge badge-success">Hoàn thành</span></td>
                                                    <td>
                                                        <a href="#" class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>#ORD-2023002</td>
                                                    <td>Trần Thị B</td>
                                                    <td>Áo khoác nữ</td>
                                                    <td>1.250.000 VNĐ</td>
                                                    <td>19/11/2023</td>
                                                    <td><span class="badge badge-warning">Đang giao</span></td>
                                                    <td>
                                                        <a href="#" class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="#" class="btn btn-warning btn-sm">
                                                            <i class="fas fa-truck"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>#ORD-2023003</td>
                                                    <td>Lê Văn C</td>
                                                    <td>Quần jean nam, Áo sơ mi</td>
                                                    <td>1.050.000 VNĐ</td>
                                                    <td>18/11/2023</td>
                                                    <td><span class="badge badge-info">Đang xử lý</span></td>
                                                    <td>
                                                        <a href="#" class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="#" class="btn btn-success btn-sm">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>#ORD-2023004</td>
                                                    <td>Phạm Thị D</td>
                                                    <td>Váy đầm dự tiệc</td>
                                                    <td>1.850.000 VNĐ</td>
                                                    <td>17/11/2023</td>
                                                    <td><span class="badge badge-primary">Đã xác nhận</span></td>
                                                    <td>
                                                        <a href="#" class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="#" class="btn btn-primary btn-sm">
                                                            <i class="fas fa-box"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>#ORD-2023005</td>
                                                    <td>Hoàng Văn E</td>
                                                    <td>Áo thun, Quần short</td>
                                                    <td>650.000 VNĐ</td>
                                                    <td>16/11/2023</td>
                                                    <td><span class="badge badge-success">Hoàn thành</span></td>
                                                    <td>
                                                        <a href="#" class="btn btn-info btn-sm">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <!-- Thống kê danh mục sản phẩm và hàng tồn kho -->
                    <div class="row">
                        <!-- Thống kê danh mục sản phẩm -->
                        <div class="col-xl-6 col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Thống kê theo danh mục</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Danh mục</th>
                                                    <th>Số lượng sản phẩm</th>
                                                    <th>Tổng doanh thu</th>
                                                    <th>Tỷ lệ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Áo nam</td>
                                                    <td>145</td>
                                                    <td>85.000.000 VNĐ</td>
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar bg-success" role="progressbar" style="width: 35%"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Áo nữ</td>
                                                    <td>98</td>
                                                    <td>65.000.000 VNĐ</td>
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar bg-info" role="progressbar" style="width: 25%"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Quần nam</td>
                                                    <td>87</td>
                                                    <td>78.000.000 VNĐ</td>
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar bg-primary" role="progressbar" style="width: 30%"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Quần nữ</td>
                                                    <td>76</td>
                                                    <td>52.000.000 VNĐ</td>
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar bg-warning" role="progressbar" style="width: 20%"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Phụ kiện</td>
                                                    <td>65</td>
                                                    <td>25.000.000 VNĐ</td>
                                                    <td>
                                                        <div class="progress">
                                                            <div class="progress-bar bg-danger" role="progressbar" style="width: 10%"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                
                        <!-- Thống kê hàng tồn kho -->
                        <div class="col-xl-6 col-lg-6">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Tình trạng tồn kho</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Sản phẩm</th>
                                                    <th>Số lượng tồn</th>
                                                    <th>Trạng thái</th>
                                                    <th>Thao tác</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Áo thun nam basic</td>
                                                    <td>52</td>
                                                    <td><span class="badge badge-success">Đủ hàng</span></td>
                                                    <td><a href="#" class="btn btn-sm btn-info">Chi tiết</a></td>
                                                </tr>
                                                <tr>
                                                    <td>Áo khoác bomber</td>
                                                    <td>15</td>
                                                    <td><span class="badge badge-warning">Sắp hết</span></td>
                                                    <td><a href="#" class="btn btn-sm btn-info">Chi tiết</a></td>
                                                </tr>
                                                <tr>
                                                    <td>Quần jean slim fit</td>
                                                    <td>35</td>
                                                    <td><span class="badge badge-success">Đủ hàng</span></td>
                                                    <td><a href="#" class="btn btn-sm btn-info">Chi tiết</a></td>
                                                </tr>
                                                <tr>
                                                    <td>Váy đầm công sở</td>
                                                    <td>8</td>
                                                    <td><span class="badge badge-danger">Cần nhập thêm</span></td>
                                                    <td><a href="#" class="btn btn-sm btn-danger">Đặt hàng</a></td>
                                                </tr>
                                                <tr>
                                                    <td>Áo sơ mi nam trắng</td>
                                                    <td>25</td>
                                                    <td><span class="badge badge-success">Đủ hàng</span></td>
                                                    <td><a href="#" class="btn btn-sm btn-info">Chi tiết</a></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
