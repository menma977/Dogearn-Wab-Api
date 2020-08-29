@extends('layouts.app')

@section('title')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>New User</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">New User</li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="card card-outline card-primary">
    <!-- /.card-header -->
    <div class="card-body p-0">
      <table id="data" class="table table-striped">
        <thead>
        <tr>
          <th style="width: 10px">#</th>
          <th>Email</th>
          <th>Sponsor</th>
          <th>Date</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $item)
          <tr>
            <td>{{ $loop->index + 1 }}.</td>
            <td>{{ $item->email }}</td>
            <td>{{ $item->sponsor->email }}</td>
            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-M-Y H:i:s') }}</td>
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
@endsection

@section('addJs')
  <!-- DataTables -->
  <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

  <script>
    $(function () {
      $('#data').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "responsive": true,
      });
    });
  </script>
@endsection