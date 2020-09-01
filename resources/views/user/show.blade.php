@extends('layouts.app')

@section('title')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h5>{{ $user->email }}</h5>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ route('user.index') }}">User List</a></li>
        <li class="breadcrumb-item active">{{ $user->email }}</li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="container-fluid">
    <div class="modal fade" id="modal-primary">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Edit User</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <i aria-hidden="true">&times;</i>
            </button>
          </div>
          <div class="modal-body">
            <div id="accordion">
              <div class="card card-primary">
                <a class="card-header" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                  <h4 class="card-title">
                    Edit password
                  </h4>
                </a>
                <div id="collapseOne" class="panel-collapse collapse in">
                  <form role="form" action="{{ route('user.updatePassword', $user->id) }}" method="post">
                    @csrf
                    <div class="card-body">
                      <div class="form-group">
                        <label for="password">New Password</label>
                        <input type="number" class="form-control @error('password') is-invalid @enderror" id="password" name="password" value="{{ old("password'") }}" placeholder="New Password">
                      </div>
                      <div class="form-group">
                        <label for="password_confirmation">Retype New Password</label>
                        <input type="number" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation"
                               value="{{ old("password_confirmation'") }}"
                               placeholder="Retype phone number">
                      </div>
                    </div>
                    <div class="card-footer">
                      <button type="submit" class="btn btn-block btn-success btn-sm">SAVE</button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="card card-danger">
                <a class="card-header" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                  <h4 class="card-title">
                    Edit Secondary Password
                  </h4>
                </a>
                <div id="collapseTwo" class="panel-collapse collapse">
                  <form role="form" action="{{ route('user.updateSecondaryPassword', $user->id) }}" method="post">
                    @csrf
                    <div class="card-body">
                      <div class="form-group">
                        <label for="new_secondary_password">New Secondary Password</label>
                        <input type="number" class="form-control @error('new_secondary_password') is-invalid @enderror" id="new_secondary_password" name="new_secondary_password"
                               value="{{ old("new_secondary_password'") }}"
                               placeholder="New Secondary Password">
                      </div>
                      <div class="form-group">
                        <label for="retype_new_secondary_password">Retype New Secondary Password</label>
                        <input type="number" class="form-control @error('retype_new_secondary_password') is-invalid @enderror" id="retype_new_secondary_password" name="retype_new_secondary_password"
                               value="{{ old("retype_new_secondary_password'") }}"
                               placeholder="Retype New Secondary Password">
                      </div>
                    </div>
                    <div class="card-footer">
                      <button type="submit" class="btn btn-block btn-success btn-sm">SAVE</button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="card card-success">
                <a class="card-header" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                  <h4 class="card-title">
                    Edit Phone
                  </h4>
                </a>
                <div id="collapseThree" class="panel-collapse collapse">
                  <form role="form" action="{{ route('user.updatePhone', $user->id) }}" method="post">
                    @csrf
                    <div class="card-body">
                      <div class="form-group">
                        <label for="new_phone_number">New Phone Number</label>
                        <input type="number" class="form-control @error('new_phone_number') is-invalid @enderror" id="new_phone_number" name="new_phone_number" value="{{ old("new_phone_number'") }}"
                               placeholder="Enter new phone number">
                      </div>
                      <div class="form-group">
                        <label for="retype_new_phone_number">Retype New Phone Number</label>
                        <input type="number" class="form-control @error('retype_new_phone_number') is-invalid @enderror" id="retype_new_phone_number" value="{{ old("retype_new_phone_number'") }}"
                               name="retype_new_phone_number"
                               placeholder="Retype phone number">
                      </div>
                    </div>
                    <div class="card-footer">
                      <button type="submit" class="btn btn-block btn-success btn-sm">SAVE</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-5">
        <div class="card card-primary card-outline">
          <div class="card-body box-profile">
            <div class="text-center">
              <img class="profile-user-img img-fluid img-circle" src="{{ asset('images/Logo.png') }}" alt="User profile picture">
            </div>

            <h3 class="profile-username text-center">{{ $user->email }}</h3>

            <p class="text-muted text-center">{{ $user->phone }}</p>

            <ul class="list-group list-group-unbordered mb-3">
              <li class="list-group-item">
                <b>Wallet</b> <a class="float-right">{{ $user->wallet }}</a>
              </li>
              <li class="list-group-item">
                <b>LOT</b> <a class="float-right">LOT {{ $grade ? $grade->id : 0 }}</a>
              </li>
              <li class="list-group-item">
                <b>Pin</b> <a class="float-right">{{ $pin }}</a>
              </li>
              <li class="list-group-item">
                <b>WA Sponsor</b> <a class="float-right">{{ $phoneSponsor }}</a>
              </li>
              <li class="list-group-item">
                <b>Balance</b>
                <a class="float-right">
                  <button id="getBalance" type="button" class="btn btn-block btn-primary btn-xs">Click To Check</button>
                </a>
              </li>
              <li class="list-group-item">
                <div class="progress">
                  @if($gradeTarget != 0)
                    <div class="progress-bar bg-primary progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                         style="width: {{ number_format(($progressGrade / $gradeTarget) * 100, 2, '.', '') }}%">
                    </div>
                  @else
                    <div class="progress-bar bg-primary progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"
                         style="width: 0">
                    </div>
                  @endif
                </div>
                <b style="font-size: 12px">{{ number_format($progressGrade / 100000000, 8, '.', '') }} DOGE</b>
                <b class="float-right" style="font-size: 12px">{{ number_format($gradeTarget / 100000000, 8, '.', '') }} DOGE</b>
              </li>
            </ul>
            <a href="#" class="btn btn-primary btn-block" data-toggle="modal" data-target="#modal-primary"><b>Edit</b></a>
          </div>
        </div>

        <div class="card card-outline card-primary">
          <div class="card-header">
            <h3 class="card-title">History LOT</h3>
          </div>
          <div class="card-body table-responsive">
            <table id="tableLot" class="table table-striped text-center" style="width: 100%">
              <thead>
              <tr>
                <th style="width: 20px">ID</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Date</th>
              </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>

        <div class="card card-outline card-primary">
          <div class="card-header">
            <h3 class="card-title">History Pin</h3>
          </div>
          <div class="card-body table-responsive">
            <table id="tablePin" class="table table-striped text-center" style="width: 100%">
              <thead>
              <tr>
                <th style="width: 20px">ID</th>
                <th>Description</th>
                <th>Amount</th>
                <th>Date</th>
              </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="col-md-7">
        <div class="card">
          <div class="card-header p-2">
            <h3 class="card-title">Data User</h3>
          </div>
          <div class="card-body">
            <div class="tab-content">
              <div class="active tab-pane" id="activity">
                <div class="post">
                  <h5>Sponsorship Line</h5>
                  <p>
                    {{ $sponsorLine }}
                  </p>
                </div>
                <div class="post table-responsive">
                  <h5>Deposits External</h5>
                  <a id="loadDeposit" class="btn btn-app">
                    <i class="fas fa-redo"></i> Refresh
                  </a>
                  <table id="tableDepositExternal" class="table table-striped text-center" style="width: 100%">
                    <thead>
                    <tr>
                      <th style="width: 20px">Currency</th>
                      <th>Date</th>
                      <th>Address</th>
                      <th>Hash</th>
                      <th>Value</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
                <div class="post table-responsive">
                  <h5>Incoming</h5>
                  <table id="tableDepositInternal" class="table table-striped text-center" style="width: 100%">
                    <thead>
                    <tr>
                      <th style="width: 20px">Currency</th>
                      <th>Date</th>
                      <th>Address</th>
                      <th>Value</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
                <div class="post table-responsive">
                  <h5>Withdrawals External</h5>
                  <a id="loadWithdraw" class="btn btn-app">
                    <i class="fas fa-redo"></i> Refresh
                  </a>
                  <table id="tableWithdrawExternal" class="table table-striped text-center" style="width: 100%">
                    <thead>
                    <tr>
                      <th style="width: 20px">Currency</th>
                      <th>Requested</th>
                      <th>Completed</th>
                      <th>Address</th>
                      <th>Hash</th>
                      <th>Value</th>
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>
                  </table>
                </div>
                <div class="post table-responsive">
                  <h5>Outgoing</h5>
                  <table id="tableWithdrawInternal" class="table table-striped text-center" style="width: 100%">
                    <thead>
                    <tr>
                      <th style="width: 20px">Currency</th>
                      <th>Requested</th>
                      <th>Completed</th>
                      <th>Address</th>
                      <th>Value</th>
                    </tr>
                    </thead>
                    <tbody>
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
    let sessionCookie = ""
    let tableDepositExternal = $('#tableDepositExternal').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
    });
    let tableDepositInternal = $('#tableDepositInternal').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
    });
    let tableWithdrawExternal = $('#tableWithdrawExternal').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
    });
    let tableWithdrawInternal = $('#tableWithdrawInternal').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
    });
    let tableLot = $('#tableLot').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
    });
    let tablePin = $('#tablePin').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": true,
      "responsive": true,
    });

    $(function () {
      setLot()
      setPin()

      $("#getBalance").click(function () {
        getBalance();
      });

      $("#loadDeposit").click(function () {
        getDeposits();
      });

      $("#loadWithdraw").click(function () {
        getWithdrawals();
      });

      @error("password")
      toastr.error("{{ $message }}");
      @enderror

      @error("new_secondary_password")
      toastr.error("{{ $message }}");
      @enderror
      @error("retype_new_secondary_password")
      toastr.error("{{ $message }}");
      @enderror

      @error("new_phone_number")
      toastr.error("{{ $message }}");
      @enderror
      @error("retype_new_phone_number")
      toastr.error("{{ $message }}");
      @enderror
    })

    function getBalance() {
      let $header = new Headers();
      $header.append("Content-Type", "application/x-www-form-urlencoded");
      $header.append("Access-Control-Allow-Origin", "*");

      let urlencoded = new URLSearchParams();
      urlencoded.append("a", "Login");
      urlencoded.append("key", "1b4755ced78e4d91bce9128b9a053cad");
      urlencoded.append("username", "{{ $user->username_doge }}");
      urlencoded.append("password", "{{ $user->password_doge }}");
      urlencoded.append("Totp", "''");

      let requestOptions = {
        method: 'POST',
        headers: $header,
        body: urlencoded,
        redirect: 'follow'
      };

      console.log(requestOptions);

      const url = "https://www.999doge.com/api/web.aspx";
      fetch(url, requestOptions).then(response => response.json()).then(result => {
        sessionCookie = result.SessionCookie;
        let balance = result.Doge.Balance;
        balance /= 100000000;
        $("#getBalance").html(balance.toFixed(8) + "DOGE");
      }).catch(error => {
        toastr.error(error);
      });
    }

    function getDeposits() {
      let $header = new Headers();
      $header.append("Content-Type", "application/x-www-form-urlencoded");
      $header.append("Access-Control-Allow-Origin", "*");

      let urlencoded = new URLSearchParams();
      urlencoded.append("a", "GetDeposits");
      urlencoded.append("s", sessionCookie);

      let requestOptions = {
        method: 'POST',
        headers: $header,
        body: urlencoded,
        redirect: 'follow'
      };

      const url = "https://www.999doge.com/api/web.aspx";
      fetch(url, requestOptions).then(response => response.json()).then(result => {
        tableDepositExternal.clear().draw();
        tableDepositInternal.clear().draw();

        for (let i = 0; i < result.Deposits.length; i++) {
          let currency = result.Deposits[i].Currency;
          let date = result.Deposits[i].Date;
          let address = result.Deposits[i].Address;
          let hash = result.Deposits[i].TransactionHash;
          let balance = result.Deposits[i].Value;
          balance /= 100000000;
          let value = balance + "DOGE";

          tableDepositExternal.row.add([
            currency,
            date,
            address,
            hash,
            value
          ]).draw();
        }

        for (let i = 0; i < result.Transfers.length; i++) {
          let currency = result.Transfers[i].Currency;
          let date = result.Transfers[i].Date;
          let address = result.Transfers[i].Address;
          let balance = result.Transfers[i].Value;
          balance /= 100000000;
          let value = balance + "DOGE";

          tableDepositInternal.row.add([
            currency,
            date,
            address,
            value
          ]).draw();
        }
      }).catch(error => {
        toastr.error(error);
      });
    }

    function getWithdrawals() {
      let $header = new Headers();
      $header.append("Content-Type", "application/x-www-form-urlencoded");
      $header.append("Access-Control-Allow-Origin", "*");

      let urlencoded = new URLSearchParams();
      urlencoded.append("a", "GetWithdrawals");
      urlencoded.append("s", sessionCookie);

      let requestOptions = {
        method: 'POST',
        headers: $header,
        body: urlencoded,
        redirect: 'follow'
      };

      const url = "https://www.999doge.com/api/web.aspx";
      fetch(url, requestOptions).then(response => response.json()).then(result => {
        tableWithdrawExternal.clear().draw();
        tableWithdrawInternal.clear().draw();

        for (let i = 0; i < result.Withdrawals.length; i++) {
          let currency = result.Withdrawals[i].Currency;
          let requested = result.Withdrawals[i].Requested;
          let completed = result.Withdrawals[i].Completed;
          let address = result.Withdrawals[i].Address;
          let hash = result.Withdrawals[i].TransactionHash;
          let balance = result.Withdrawals[i].Value;
          balance /= 100000000;
          let value = balance + "DOGE";

          tableWithdrawExternal.row.add([
            currency,
            requested,
            completed,
            address,
            hash,
            value
          ]).draw();
        }

        for (let i = 0; i < result.Transfers.length; i++) {
          let currency = result.Transfers[i].Currency;
          let requested = result.Transfers[i].Requested;
          let completed = result.Transfers[i].Completed;
          let address = result.Transfers[i].Address;
          let balance = result.Transfers[i].Value;
          balance /= 100000000;
          let value = balance + "DOGE";

          tableWithdrawInternal.row.add([
            currency,
            requested,
            completed,
            address,
            value
          ]).draw();
        }
      }).catch(error => {
        toastr.error(error);
      });
    }

    function setLot() {
      let url = "{{ route('user.lotList', $user->id) }}";
      fetch(url, {
        method: 'GET',
        headers: new Headers({
          'Content-Type': 'application/x-www-form-urlencoded',
          "X-CSRF-TOKEN": $("input[name='_token']").val()
        }),
      }).then((response) => response.json()).then((data) => {
        tableLot.clear().draw();
        for (let i = 0; i < data.length; i++) {
          let debit = data[i].debit;
          let credit = data[i].credit;
          let level = data[i].upgrade_level;

          let description = ""
          let balance = ""
          if (debit == 0) {
            description = data[i].email + " Send: " + credit / 100000000 + " DOGE. Upgrade Level" + level;
            balance = "-" + credit / 100000000 + " DOGE"
          } else {
            description = data[i].email + " Send: " + debit / 100000000 + " DOGE. Upgrade Level" + level;
            balance = "+" + debit / 100000000 + " DOGE"
          }
          let date = data[i].date;
          let id = i + 1 + ".";
          tableLot.row.add([
            id,
            description,
            balance,
            date
          ]).draw();
        }
      });
    }

    function setPin() {
      let url = "{{ route('user.pinList', $user->id) }}";
      fetch(url, {
        method: 'GET',
        headers: new Headers({
          'Content-Type': 'application/x-www-form-urlencoded',
          "X-CSRF-TOKEN": $("input[name='_token']").val()
        }),
      }).then((response) => response.json()).then((data) => {
        tablePin.clear().draw();
        for (let i = 0; i < data.length; i++) {
          let debit = data[i].debit;
          let credit = data[i].credit;

          let balance = ""
          if (debit == 0) {
            balance = "-" + credit;
          } else {
            balance = "+" + debit;
          }
          let date = data[i].date;
          let id = i + 1 + ".";
          tablePin.row.add([
            id,
            data[i].description,
            balance,
            date
          ]).draw();
        }
      });
    }
  </script>
@endsection