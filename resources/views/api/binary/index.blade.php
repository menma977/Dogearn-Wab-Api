@extends('layouts.appApi')

@section('title')
  <div class="row mb-2">
    <div class="col-sm-6">
      <h1>Network</h1>
    </div>
    <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item">
          <a href="{{ route('home') }}">
            Home
          </a>
        </li>
        <li class="breadcrumb-item active">
          Network
        </li>
      </ol>
    </div>
  </div>
@endsection

@section('content')
  <div class="table-responsive">
    <ul class="tree" style="min-width: 1000px">
      <li>
        <div class="fa fa-minus-circle" style="min-width: 200px">
          {{ Auth::user()->email }} <i class="fa fa-trophy text-danger" style="font-size: 10px;">{{ Auth::user()->level }}</i>
        </div>
        <ul class="nested">
          @foreach ($binary as $item)
            @if($item->userDownLine->status === 2)
              <li>
                <a href="#" id="caret-{{ $item->down_line }}" class="fa fa-plus-circle" onclick="addCaret('{{ $item->down_line }}')" style="min-width: 200px">
                  @if ($item->userDownLine)
                    {{ $item->userDownLine->email }} <i class="fa fa-trophy text-danger" style="font-size: 10px;">{{ $item->userDownLine->level }}</i>
                  @endif
                </a>
                <div id="{{ $item->down_line }}"></div>
              </li>
            @endif
          @endforeach
        </ul>
      </li>
    </ul>
  </div>
@endsection

@section('addCss')
  <style>
      ul.tree,
      ul.tree ul {
          list-style: none;
          margin: 0;
          padding: 0;
      }

      ul.tree ul {
          margin-left: 10px;
      }

      ul.tree li {
          margin: 0;
          padding: 0 10px;
          line-height: 20px;
          font-weight: bold;
      }

      ul.tree li:last-child {
          border-left: none;
      }

      ul.tree li:before {
          position: relative;
          top: -0.3em;
          height: 1em;
          width: 12px;
          color: white;
          content: "";
          display: inline-block;
          left: -7px;
      }
  </style>
@endsection

@section('addJs')
  <script>
    function addCaret(user) {
      if (document.getElementById('caret-' + user).className === "fa fa-minus-circle") {
        document.getElementById(user).innerHTML = "";
        document.getElementById('caret-' + user).className = "fa fa-plus-circle";
      } else {
        document.getElementById('caret-' + user).className = "fa fa-minus-circle";
        let url = "{{ route('binary.api.show', '%data%') }}";
        url = url.replace('%data%', user);
        fetch(url, {
          method: 'GET',
          headers: new Headers({
            'Content-Type': 'application/x-www-form-urlencoded',
            "X-CSRF-TOKEN": $("input[name='_token']").val(),
            "Authorization": "Bearer {{ $token }}",
            "Access-Control-Allow-Origin": "*",
          }),
        }).then((response) => response.json()).then((responseData) => {
          let idSponsor = 0;
          let htmlBody = '';
          responseData.forEach(element => {
            if (element.userDownLine.status === 2) {
              idSponsor = element.sponsor;
              let user = '<li>' +
                '<a href="#" id="caret-'
                + element.down_line
                + '" class="fa fa-plus-circle" onclick="addCaret(`%data%`)" style="min-width: 200px"> '
                + element.userDownLine.email
                + '<i class="fa fa-trophy text-danger" style="font-size: 10px;">'
                + element.userDownLine.level
                +'</i>'
                + '</a> <div id="'
                + element.down_line + '"></div>'
                + '</li>';
              user = user.replace('%data%', element.down_line);
              htmlBody += '<ul class="nested active">' + user + '</ul>';
            }
          });
          document.getElementById(idSponsor).innerHTML = htmlBody;
        }).catch((error) => {
          //console.log(error);
        });
      }
    }
  </script>
@endsection