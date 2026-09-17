@extends('layouts.admin')

@section('title', 'Quản Lý Khách Hàng - FloraCharm Admin')
@section('page_title', 'Danh Sách Khách Hàng & Thành Viên')

@section('content')
<div class="card border-0 shadow-sm rounded-4 bg-white p-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
        <div>
            <h5 class="fw-bold mb-0 font-serif">Khách Hàng Đã Đăng Ký (CRM)</h5>
            <small class="text-muted">Theo dõi danh sách người dùng và tổng giá trị mua hàng tích lũy.</small>
        </div>

        <form action="{{ route('admin.users.index') }}" method="GET" class="d-flex gap-2" style="max-width: 350px;">
            <div class="input-group">
                <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control rounded-start-pill" placeholder="Tìm tên, email, SĐT...">
                <button type="submit" class="btn btn-danger rounded-end-pill px-3">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </button>
            </div>
            @if(request('keyword'))
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary rounded-pill">
                    <i class="fa-solid fa-xmark"></i>
                </a>
            @endif
        </form>
    </div>

    <div class="table-responsive">
        <table class="table align-middle table-hover">
            <thead class="table-light">
                <tr>
                    <th>Khách Hàng</th>
                    <th>Email</th>
                    <th>Số Điện Thoại</th>
                    <th>Địa Chỉ Mặc Định</th>
                    <th>Số Đơn Đã Đặt</th>
                    <th>Tổng Tiền Mua</th>
                    <th>Ngày Tham Gia</th>
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $u)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="rounded-circle bg-danger-subtle text-danger d-flex align-items-center justify-content-center me-2 fw-bold" style="width: 38px; height: 38px;">
                                    {{ strtoupper(substr($u->name, 0, 1)) }}
                                </div>
                                <div>
                                    <strong>{{ $u->name }}</strong>
                                    <div class="small text-muted">ID: #{{ $u->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->phone ?: 'Chưa cập nhật' }}</td>
                        <td>
                            <span class="text-truncate d-inline-block" style="max-width: 200px;" title="{{ $u->address }}">
                                {{ $u->address ?: 'Chưa cập nhật' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-1">
                                {{ $u->orders_count }} đơn
                            </span>
                        </td>
                        <td class="fw-bold text-danger">
                            {{ number_format($u->orders_sum_total_amount ?: 0) }} đ
                        </td>
                        <td class="small text-muted">
                            {{ $u->created_at->format('d/m/Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">Chưa có khách hàng nào phù hợp với tìm kiếm.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3 d-flex justify-content-center">
        {{ $customers->links() }}
    </div>
</div>
@endsection