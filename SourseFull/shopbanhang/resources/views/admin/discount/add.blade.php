@extends('admin.main')

@section('head')
<script src="/ckeditor/ckeditor.js"></script>
@endsection

@section('content')
<form action="{{ url('admin/discounts/add') }}" method="POST">
    <div class="card-body">

        <div class="form-group">
            <label for="code">Mã Giảm Giá</label>
            <input type="text" name="code" class="form-control" placeholder="Nhập mã giảm giá">
        </div>

        <div class="form-group">
            <label for="percentage">Phần Trăm Giảm Giá (%)</label>
            <input type="number" name="percentage" class="form-control" placeholder="Nhập phần trăm giảm giá">
        </div>

        <div class="form-group">
            <label for="quantity">Số Lượng</label>
            <input type="number" name="quantity" class="form-control" placeholder="Nhập số lượng mã giảm giá" required min="0">
        </div>

        <div class="form-group">
            <label for="start_date">Ngày Bắt Đầu</label>
            <input type="date" name="start_date" class="form-control">
        </div>

        <div class="form-group">
            <label for="end_date">Ngày Kết Thúc</label>
            <input type="date" name="end_date" class="form-control">
        </div>

        <div class="form-group">
            <label>Kích Hoạt</label>
            <div class="custom-control custom-radio">
                <input class="custom-control-input" value="1" type="radio" id="active" name="active" checked>
                <label for="active" class="custom-control-label">Có</label>
            </div>
            <div class="custom-control custom-radio">
                <input class="custom-control-input" value="0" type="radio" id="no_active" name="active">
                <label for="no_active" class="custom-control-label">Không</label>
            </div>
        </div>

    </div>

    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Tạo Mã Giảm Giá</button>
    </div>
    @csrf
</form>
@endsection

@section('footer')
<script>
    CKEDITOR.replace('content');
</script>
@endsection