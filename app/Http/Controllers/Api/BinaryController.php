<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Model\Binary;
use App\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BinaryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @param Request $request
   * @return array|Application|Factory|View
   */
  public function index(Request $request)
  {
    $token = $request->bearerToken();
    $binary = Binary::where('sponsor', Auth::user()->id)->get();
    $binary->map(function ($item) {
      $item->userDownLine = User::find($item->down_line);
    });

    $data = [
      'binary' => $binary,
      'token' => $token
    ];

    return view('api.binary.index', $data);
  }

  /**
   * Display the specified resource.
   *
   * @param $id
   * @return Response
   */
  public function show($id)
  {
    $binary = Binary::where('sponsor', $id)->get();
    $binary->map(function ($item) {
      $item->userDownLine = User::find($item->down_line);
    });

    return $binary;
  }
}
