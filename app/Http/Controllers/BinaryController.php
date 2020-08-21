<?php

namespace App\Http\Controllers;

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
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Display a listing of the resource.
   *
   * @return Application|Factory|Response|View
   */
  public function index()
  {
    $binary = Binary::where('sponsor', Auth::user()->id)->get();
    $binary->map(function ($item) {
      $item->userDownLine = User::find($item->down_line);
    });

    $data = [
      'binary' => $binary
    ];

    return view('binary.index', $data);
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
      $item->userDownLine = User::find($item->user);
    });

    return $binary;
  }
}
