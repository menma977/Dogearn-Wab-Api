@extends('layouts.app')

@section('title')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Send Pin</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Send Pin</li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <form action="{{ route('pin.store') }}" method="post">
    @csrf
    <div class="card card-outline card-primary">
      <div class="card-header">
        <h3 class="card-title">Send Pin Form</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="form-group">
          <label for="user">User</label>
          <select id="user" name="user" class="form-control select2 select2-primary" data-dropdown-css-class="select2-primary" style="width: 100%;">
            @foreach($users as $item)
              <option value="{{ $item->id }}" {{ old('user') === $item->id ? 'selected' : '' }}>{{ $item->email }}</option>
            @endforeach
          </select>
        </div>
        <div class="input-group mb-3">
          <div class="input-group-prepend">
            <label for="pin" class="input-group-text">PIN</label>
          </div>
          <input type="number" class="form-control @error('total_pin') is-invalid @enderror" placeholder="Total Pin" id="pin" name="total_pin" value="{{ old('total_pin') }}">
        </div>
      </div>
      <div class="card-footer">
        <button type="submit" class="btn btn-block btn-outline-primary">Send</button>
      </div>
    </div>
  </form>

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">List Transaction</h3>
    </div>
    <div class="card-body p-0">
      <table class="table table-sm" id="table" style="width: 100%">
        <thead class="text-center">
        <tr>
          <th style="width: 20px">#</th>
          <th>Email</th>
          <th>description</th>
          <th style="width: 40px">Send</th>
          <th style="width: 40px">Received</th>
          <th>Date</th>
        </tr>
        </thead>
        <tbody class="text-center">
        @foreach($pinLedgers as $item)
          <tr>
            <td>
              {{ $loop->index + 1 }}.
            </td>
            <td>
              {{ $item->email }}
            </td>
            <td>
              {{ $item->description }}
            </td>
            <td>
              {{ $item->debit }}
            </td>
            <td>
              {{ $item->credit }}
            </td>
            <td>
              {{ \Carbon\Carbon::parse($item->created_at)->format('d-M-Y H:i:s') }}
            </td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
@endsection

@section('addCss')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
@endsection

@section('addJs')
  <!-- DataTables -->
  <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

  <!-- Select2 -->
  <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

  <!-- Toastr -->
  <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

  <script>
    $(function () {
      $('.select2').select2();

      $('#table').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "responsive": true,
      });

      @error('user')
      toastr.error('{{ $message }}');
      @enderror

      @error('total_pin')
      toastr.error('{{ $message }}');
      @enderror
    });
  </script>
@endsection