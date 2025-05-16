@extends('admin.layout.AdminLayout')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Chi tiết sách</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($book->cover_image)
                                <img src="{{ asset($book->cover_image) }}" alt="{{ $book->title }}" class="img-fluid rounded">
                            @else
                                <div class="text-center text-muted">
                                    <i class="fas fa-book fa-5x"></i>
                                    <p class="mt-2">Không có ảnh</p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <table class="table table-bordered">
                                <tr>
                                    <th style="width: 200px">Tên sách</th>
                                    <td>{{ $book->title }}</td>
                                </tr>
                                <tr>
                                    <th>Tác giả</th>
                                    <td>{{ $book->author }}</td>
                                </tr>
                                <tr>
                                    <th>Nhà xuất bản</th>
                                    <td>{{ $book->publisher }}</td>
                                </tr>
                                <tr>
                                    <th>Danh mục</th>
                                    <td>{{ $book->category->name }}</td>
                                </tr>
                                <tr>
                                    <th>Giá</th>
                                    <td>{{ number_format($book->price) }} VNĐ</td>
                                </tr>
                                <tr>
                                    <th>Số lượng tồn kho</th>
                                    <td>{{ $book->stock }}</td>
                                </tr>
                                <tr>
                                    <th>Trạng thái</th>
                                    <td>
                                        <span class="badge badge-{{ $book->status == 'còn hàng' ? 'success' : ($book->status == 'hết hàng' ? 'danger' : 'warning') }}">
                                            {{ $book->status }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Ngày tạo</th>
                                    <td>{{ $book->created_at->format('d/m/Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <th>Cập nhật lần cuối</th>
                                    <td>{{ $book->updated_at->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>

                            <div class="mt-4">
                                <h4>Mô tả</h4>
                                <p class="text-justify">{{ $book->description }}</p>
                            </div>

                            <div class="mt-4">
                                <a href="{{ route('books.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Quay lại
                                </a>
                                <a href="{{ route('books.edit', $book) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Chỉnh sửa
                                </a>
                                <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa sách này?')">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 