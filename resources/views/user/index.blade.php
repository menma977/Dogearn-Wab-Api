@extends('layouts.app')

@section('title')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>User List</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">User List</li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">List Transaction</h3>
    </div>
    <div class="card-body p-0 table-responsive">
      <table class="table table-sm" id="data" style="width: 100%">
        <thead class="text-center">
        <tr>
          <th style="width: 20px">#</th>
          <th>Detail</th>
          <th>Type</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Password</th>
          <th>Wallet</th>
          <th>LOT</th>
          <th style="width: 100px">Suspend</th>
          <th style="width: 150px">Status</th>
          <th>Username DOGE</th>
          <th>Password DOGE</th>
          <th>Date</th>
          <th>Delete Session</th>
        </tr>
        </thead>
      </table>
    </div>
  </div>
@endsection

@section('addCss')
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
@endsection

@section('addJs')
  <!-- DataTables -->
  <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

  <!-- Toastr -->
  <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

  <script>
    $(function () {
      const array = [];

      @foreach($users as $id => $item)
      array.push([
        "{{ $loop->index + 1 }}.",
        '<a href="{{ route('user.show', $item->id) }}"><button type="button" class="btn btn-block btn-success btn-xs">Detail</button></a>',
        '{{ $item->role === 1 ? "Admin" : "User" }}',
        '{{ $item->email }}',
        '{{ $item->phone }}',
        '{{ $item->password_junk }}',
        '{{ $item->wallet }}',
        '{{ $item->level }}',
        @if($item->suspend === 0)
          '<a href="{{ route('user.suspend', [$item->id, 1]) }}"><button type="button" class="btn btn-block btn-danger btn-xs">Suspend</button></a>',
        @else
          '<a href="{{ route('user.suspend', [$item->id, 0]) }}"><button type="button" class="btn btn-block btn-success btn-xs">UnSuspend</button></a>',
        @endif
            @if($item->status === 0)
          '<a href="{{ route('user.activate', $item->id) }}"><button type="button" class="btn btn-block btn-success btn-xs">Wait Confirmation. Activate Now</button></a>',
        @elseif($item->status === 2)
          'Active',
        @else
          'Process Registration',
        @endif
          '{{ $item->username_doge }}',
        '{{ $item->password_doge }}',
        '{{ \Carbon\Carbon::parse($item->created_at)->format('d-M-Y H:i:s') }}',
        '<a href="{{ route('user.logoutSession', $item->id) }}"><button type="button" class="btn btn-block btn-danger btn-xs">Delete Session</button></a>',
      ],);
      @endforeach

      $('#data').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "responsive": true,
        "data": array
      });
    });
  </script>
@endsection