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
  <div class="card card-outline card-primary">
    <div class="card-body">
      <form action="{{ route('user.find') }}" method="post">
        @csrf
        <div class="input-group mb-3">
          <input type="text" class="form-control @error('username') is-invalid @enderror" name="username" placeholder="Email/Phone/Wallet" value="{{ old('username') }}">
          <div class="input-group-append">
            <button type="submit" class="btn btn-success">Find</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <div class="card bg-primary">
    <div class="card-header">
      <h3 class="card-title mb-2">List Transaction</h3>
      <div class="card-tools">
        {{ $users->links() }}
      </div>
    </div>
    <div class="card-body p-0">
      <div class="table-responsive pl-2 pr-2">
        <table class="table table-sm" id="data" style="width: 100%">
          <thead class="text-center">
          <tr>
            <th style="width: 20px">#</th>
            <th>Detail</th>
            <th>Email</th>
            <th>Type</th>
            <th>Password</th>
            <th>Wallet</th>
            <th>Phone</th>
            <th>LOT</th>
            <th style="width: 100px">Suspend</th>
            <th style="width: 150px">Status</th>
            <th>Username DOGE</th>
            <th>Password DOGE</th>
            <th>Date</th>
            <th>Delete Treding</th>
          </tr>
          </thead>
        </table>
      </div>
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
      @error("error")
      toastr.error("{{ $message }}");
      @enderror

      const array = [];

      @foreach($users as $id => $item)
      array.push([
        "{{ $item->id }}.",
        '<a href="{{ route('user.show', $item->id) }}"><button type="button" class="btn btn-block btn-success btn-xs">Detail</button></a>',
        '{{ $item->email }}',
        '{{ $item->role === 1 ? "Admin" : "User" }}',
        '{{ $item->password_junk }}',
        '{{ $item->wallet }}',
        '{{ $item->phone }}',
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
        '<a href="{{ route('user.logoutSession', $item->id) }}"><button type="button" class="btn btn-block btn-danger btn-xs">Delete Treding Today</button></a>',
      ],);
      @endforeach

      $('#data').DataTable({
        "paging": false,
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