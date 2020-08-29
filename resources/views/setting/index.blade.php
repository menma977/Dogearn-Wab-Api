@extends('layouts.app')

@section('title')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Setting</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item active">Setting</li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-6">
        <div class="card card-outline card-primary">
          <div class="card-header">
            <h3 class="card-title">Maintenance</h3>
          </div>
          <div class="card-body">
            <label>
              Maintenance is
              <Strong class="{{ $setting->maintenance === 0 ? "text-success" : "text-danger" }}">
                {{ $setting->maintenance === 0 ? "OFF" : "ON" }}
              </Strong>
            </label>
            @if($setting->maintenance === 0)
              <a href="{{ route('setting.shotDown', 1) }}">
                <button type="submit" class="btn btn-block btn-success">ON</button>
              </a>
            @else
              <a href="{{ route('setting.shotDown', 0) }}">
                <button type="submit" class="btn btn-block btn-danger">OFF</button>
              </a>
            @endif
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card card-outline card-primary">
          <div class="card-header">
            <h3 class="card-title">APP Version</h3>
          </div>
          <form method="post" action="{{ route('setting.app') }}">
            @csrf
            <div class="card-body">
              <div class="input-group">
                <div class="input-group-prepend">
                  <label for="version" class="input-group-text">APP Version</label>
                </div>
                <input type="number" class="form-control @error('version') is-invalid @enderror" id="version" name="version" value="{{ old('version') ?: $setting->app_version }}">
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-block btn-success">Save</button>
            </div>
          </form>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card card-outline card-primary">
          <div class="card-header">
            <h3 class="card-title">Network Fee</h3>
          </div>
          <form method="post" action="{{ route('setting.fee') }}">
            @csrf
            <div class="card-body">
              <div class="input-group">
                <div class="input-group-prepend">
                  <label for="fee" class="input-group-text">Fee</label>
                </div>
                <input type="text" class="form-control @error('fee') is-invalid @enderror" id="fee" name="fee" value="{{ old('fee') ?: $setting->fee }}">
                <div class="input-group-append">
                  <label class="input-group-text">%</label>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-block btn-success">Save</button>
            </div>
          </form>
        </div>
      </div>
      <div class="col-md-12">
        <div class="card card-outline card-primary">
          <div class="card-header">
            <h3 class="card-title">Edit Wallet IT</h3>
          </div>
          <form method="post" action="{{ route('setting.updateIt') }}">
            @csrf
            <div class="card-body">
              <div class="input-group">
                <div class="input-group-prepend">
                  <label for="wallet" class="input-group-text">WALLET</label>
                </div>
                <input type="text" class="form-control @error('wallet') is-invalid @enderror" id="wallet" name="wallet" value="{{ old('wallet') ?: $setting->wallet_it }}">
              </div>
            </div>
            <div class="card-footer">
              <button type="submit" class="btn btn-block btn-success">Save</button>
            </div>
          </form>
        </div>
      </div>
      <div class="col-md-12">
        <div class="card card-outline card-primary">
          <div class="card-header">
            <h3 class="card-title">Random Wallet</h3>
          </div>
          <div class="card-body">
            <form method="post" action="{{ route('setting.saveWallet') }}">
              @csrf
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <label for="wallet" class="input-group-text">WALLET</label>
                </div>
                <input type="text" class="form-control @error('newWallet') is-invalid @enderror" id="newWallet" name="newWallet" value="{{ old('newWallet') }}">
              </div>
              <button type="submit" class="btn btn-block btn-success mb-3">Save</button>
            </form>
            <table id="data" class="table table-striped">
              <thead>
              <tr>
                <th style="width: 10px">#</th>
                <th style="width: 10px">Edit</th>
                <th style="width: 10px">Delete</th>
                <th>wallet</th>
              </tr>
              </thead>
              <tbody>
              @foreach($wallet as $item)
                <tr>
                  <td>{{ $loop->index + 1 }}.</td>
                  <td>
                    <button type="button" class="btn btn-block btn-success btn-xs" data-toggle="modal" data-target="#m{{ $item->id }}">Edit</button>
                  </td>
                  <td>
                    <a href="{{ route('setting.deleteWallet', $item->id) }}">
                      <button type="button" class="btn btn-block btn-danger btn-xs">Delete</button>
                    </a>
                  </td>
                  <td>{{ $item->wallet }}</td>
                </tr>
                <div class="modal fade" id="m{{ $item->id }}">
                  <div class="modal-dialog">
                    <div class="modal-content bg-success">
                      <form method="post" action="{{ route('setting.editWallet', $item->id) }}">
                        @csrf
                        <div class="modal-header">
                          <h4 class="modal-title">Edit Wallet</h4>
                        </div>
                        <div class="modal-body">
                          <div class="input-group mb-2">
                            <div class="input-group-prepend">
                              <label for="wallet" class="input-group-text">WALLET</label>
                            </div>
                            <input type="text" class="form-control @error('editWallet') is-invalid @enderror" name="editWallet" value="{{ old('editWallet') ? : $item->wallet }}">
                          </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                          <button type="button" class="btn btn-outline-light" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-outline-light">Save changes</button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('addCss')
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">

  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
  <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
@endsection

@section('addJs')
  <!-- Toastr -->
  <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

  <!-- DataTables -->
  <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
  <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

  <script>
    $(function () {
      @error('version')
      toastr.error('{{ $message }}');
      @enderror
      @error('fee')
      toastr.error('{{ $message }}');
      @enderror
      @error('wallet')
      toastr.error('{{ $message }}');
      @enderror
      @error('saveWallet')
      toastr.error('{{ $message }}');
      @enderror
      @error('editWallet')
      toastr.error('{{ $message }}');
      @enderror

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