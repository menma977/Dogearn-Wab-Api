@extends('layouts.app')

@section('title')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Home</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item active">Home</li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <form action="{{ route('find') }}" method="post">
    @csrf
    <div class="form-group">
      <label>Date range:</label>
      <div class="input-group">
        <div class="input-group-prepend">
          <div class="input-group-text">
            <i class="far fa-calendar-alt"></i>
          </div>
        </div>
        <input type="text" class="form-control float-right" id="reservation" name="dateFrom" value="{{ $dateFrom->format('m/d/Y')?: '' }}">
        <input type="text" class="form-control float-right" id="reservation2" name="dateNow" value="{{ $dateNow->format('m/d/Y')?: '' }}">
        <div class="input-group-append">
          <button type="submit" class="btn btn-info">Find</button>
        </div>
      </div>
    </div>
  </form>
  <div class="row">
    <div class="col-md-3">
      <div class="small-box bg-primary">
        <div class="inner">
          <h3 id="totalUserText">0</h3>
          <p>Total User</p>
        </div>
        <div class="icon">
          <i class="fas fa-users"></i>
        </div>
        <a href="{{ route('user.index') }}" class="small-box-footer">
          More info <i class="fas fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
    <div class="col-md-3">
      <div class="small-box bg-info">
        <div class="inner">
          <h3 id="onlineUserText">0</h3>
          <p>Online Users</p>
        </div>
        <div class="icon">
          <i class="fas fa-street-view"></i>
        </div>
        <a href="{{ route('onlineUserView', [$dateFrom, $dateNow]) }}" class="small-box-footer">
          More info <i class="fas fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
    <div class="col-md-3">
      <div class="small-box bg-warning">
        <div class="inner">
          <h3 id="newUserText">0</h3>
          <p>New User Today</p>
        </div>
        <div class="icon">
          <i class="fas fa-user-plus"></i>

        </div>
        <a href="{{ route('newUserView', [$dateFrom, $dateNow]) }}" class="small-box-footer">
          More info <i class="fas fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
    <div class="col-md-3">
      <div class="small-box bg-success">
        <div class="inner">
          <h3 id="totalUpgradeText">0</h3>
          <p>Total LOT Today</p>
        </div>
        <div class="icon">
          <i class="fas fa-trophy"></i>
        </div>
        <a href="{{ route('totalUpgradeView', [$dateFrom, $dateNow]) }}" class="small-box-footer">
          More info <i class="fas fa-arrow-circle-right"></i>
        </a>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-md-12">
      <a href="{{ route('newUserNotVerifiedView') }}">
        <div class="info-box bg-danger">
          <span class="info-box-icon"><i class="fas fa-user-clock"></i></span>

          <div class="info-box-content">
            <span class="info-box-text">User Not Verified</span>
            <span class="info-box-number" id="userNotVerified">0</span>
            <div class="progress">
              <div class="progress-bar" id="userNotVerifiedLine" style="width: 0"></div>
            </div>
            <div class="progress-description" id="userNotVerifiedDescription">
            </div>
          </div>
        </div>
      </a>
    </div>
  </div>

  <div class="row">
    <div class="col-md-6">
      <div class="card card-outline card-primary">
        <div class="card-header">
          <h3 class="card-title">Lot</h3>
        </div>
        <div class="card-body">
          <canvas id="barChartLot" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card card-outline card-primary">
        <div class="card-header">
          <h3 class="card-title">New User And LOT in 1 Month</h3>
        </div>
        <div class="card-body">
          <div class="chart">
            <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('addCss')
  <!-- daterange picker -->
  <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
@endsection

@section('addJs')
  <!-- ChartJS -->
  <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>

  <!-- InputMask -->
  <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
  <script src="{{ asset('plugins/inputmask/min/jquery.inputmask.bundle.min.js') }}"></script>
  <!-- date-range-picker -->
  <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
  <!-- Toastr -->
  <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>

  <script>
    let barChartLot;
    let barChart;

    $(async function () {
      @error("dateFrom")
      toastr.error("{{ $message }}");
      @enderror
      @error("dateNow")
      toastr.error("{{ $message }}");
      @enderror

      //Date range picker
      $('#reservation').daterangepicker({
        singleDatePicker: true,
        timePicker: false,
      })
      $('#reservation2').daterangepicker({
        singleDatePicker: true,
        timePicker: false,
      })

      setGraphic();
      setGraphicLot();
      $('#totalUserText').html(await totalUser());
      $('#onlineUserText').html(await onlineUser());
      $('#newUserText').html(await newUser());
      $('#totalUpgradeText').html(await totalUpgrade());

      $('#userNotVerified').html(await newUserNotVerified());
      $('#userNotVerifiedLine').width((await totalUser() - await newUserNotVerified()) + "%");
      let description = "user not verified: " + await newUserNotVerified() + " .from total user: " + await totalUser()
      $('#userNotVerifiedDescription').html(description);

      await setInterval(async function () {
        $('#totalUserText').html(await totalUser());
        $('#onlineUserText').html(await onlineUser());
        $('#newUserText').html(await newUser());
        $('#totalUpgradeText').html(await totalUpgrade());

        $('#userNotVerified').html(await newUserNotVerified());
        $('#userNotVerifiedLine').width((await totalUser() - await newUserNotVerified()) + "%");
        let description = "user not verified: " + await newUserNotVerified() + " .from total user: " + await totalUser()
        $('#userNotVerifiedDescription').html(description);
      }, 5000);
    });

    async function totalUser() {
      return await fetch("{{ route('totalUser') }}", {
        method: 'GET',
        headers: new Headers({
          'Content-Type': 'application/x-www-form-urlencoded',
          "X-CSRF-TOKEN": $("input[name='_token']").val()
        }),
      }).then(async (response) => {
        return await response.json()
      });
    }

    async function onlineUser() {
      return await fetch("{{ route('onlineUser') }}", {
        method: 'GET',
        headers: new Headers({
          'Content-Type': 'application/x-www-form-urlencoded',
          "X-CSRF-TOKEN": $("input[name='_token']").val()
        }),
      }).then(async (response) => {
        return await response.json()
      });
    }

    async function newUser() {
      let url = "{{ route('newUser', ["%date1%", "%date2%"]) }}";
      url = url.replace('%date1%', "{{$dateFrom}}");
      url = url.replace('%date2%', "{{$dateNow}}");
      return await fetch(url, {
        method: 'GET',
        headers: new Headers({
          'Content-Type': 'application/x-www-form-urlencoded',
          "X-CSRF-TOKEN": $("input[name='_token']").val()
        }),
      }).then(async (response) => {
        return await response.json()
      });
    }

    async function totalUpgrade() {
      let url = "{{ route('totalUpgrade', ["%date1%", "%date2%"]) }}";
      url = url.replace('%date1%', "{{$dateFrom}}");
      url = url.replace('%date2%', "{{$dateNow}}");
      return await fetch(url, {
        method: 'GET',
        headers: new Headers({
          'Content-Type': 'application/x-www-form-urlencoded',
          "X-CSRF-TOKEN": $("input[name='_token']").val()
        }),
      }).then(async (response) => {
        return await response.json()
      });
    }

    async function newUserNotVerified() {
      return await fetch("{{ route('newUserNotVerified') }}", {
        method: 'GET',
        headers: new Headers({
          'Content-Type': 'application/x-www-form-urlencoded',
          "X-CSRF-TOKEN": $("input[name='_token']").val()
        }),
      }).then(async (response) => {
        return await response.json()
      });
    }

    function setGraphicLot() {
      const arrayLabel = [];
      const arrayData = [];

      @foreach($lot as $id => $item)
      arrayLabel.push("{{$id}}")
      arrayData.push("{{$item->total}}")
      @endforeach

      const areaChartData = {
        labels: arrayLabel,
        datasets: [
          {
            label: 'LOT',
            backgroundColor: '#007bff',
            borderColor: '#006ee5',
            pointRadius: false,
            pointColor: '#007bff',
            pointStrokeColor: '#007bff',
            pointHighlightFill: '#fff',
            pointHighlightStroke: '#006ee5',
            data: arrayData
          }
        ]
      }

      const barChartCanvas = $('#barChartLot').get(0).getContext('2d')
      const barChartData = jQuery.extend(true, {}, areaChartData)
      barChartData.datasets[0] = areaChartData.datasets[0]

      const barChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false
      }

      barChart = new Chart(barChartCanvas, {
        type: 'bar',
        data: barChartData,
        options: barChartOptions
      })
    }

    function setGraphic() {
      const arrayLabel = [];
      const arrayData1 = [];
      const arrayData2 = [];

      @foreach($graphicGroup as $id => $item)
      arrayLabel.push("{{$id}}")
      arrayData1.push("{{$item->upgrade}}")
      arrayData2.push("{{$item->newUser}}")
      @endforeach

      const areaChartData = {
        labels: arrayLabel,
        datasets: [
          {
            label: 'LOT',
            backgroundColor: '#28a745',
            borderColor: '#24963e',
            pointRadius: false,
            pointColor: '#28a745',
            pointStrokeColor: '#28a745',
            pointHighlightFill: '#fff',
            pointHighlightStroke: '#24963e',
            data: arrayData1
          },
          {
            label: 'New User',
            backgroundColor: '#ffc107',
            borderColor: '#e5ad06',
            pointRadius: false,
            pointColor: '#ffc107',
            pointStrokeColor: '#e5ad06',
            pointHighlightFill: '#fff',
            pointHighlightStroke: '#ffc107',
            data: arrayData2
          },
        ]
      }

      const barChartCanvas = $('#barChart').get(0).getContext('2d')
      const barChartData = jQuery.extend(true, {}, areaChartData)
      barChartData.datasets[0] = areaChartData.datasets[0]
      barChartData.datasets[1] = areaChartData.datasets[1]

      const barChartOptions = {
        responsive: true,
        maintainAspectRatio: false,
        datasetFill: false
      }

      barChart = new Chart(barChartCanvas, {
        type: 'bar',
        data: barChartData,
        options: barChartOptions
      })
    }
  </script>
@endsection
