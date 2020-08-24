<?php

namespace App\Http\Controllers;

use App\Model\Grade;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class GradeController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return Application|Factory|View|void
   */
  public function index()
  {
    $grade = Grade::all();

    $data = [
      'grade' => $grade
    ];

    return view('grade.index', $data);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param Request $request
   * @return RedirectResponse
   * @throws ValidationException
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'price' => 'required|numeric',
      'pin' => 'required|numeric',
    ]);

    $grade = new Grade();
    if (strpos($request->price, ".") !== false || strpos($request->price, ",") !== false) {
      $rawBalance = str_replace(array(".", ","), "", $request->price);
      $balance = number_format($rawBalance * 100000000, 0, '.', '');
    } else {
      $balance = number_format($request->price * 100000000, 0, '.', '');
    }
    $grade->price = $balance;
    $grade->pin = $request->pin;
    $grade->save();

    return redirect()->back();
  }

  /**
   * Update the specified resource in storage.
   *
   * @param Request $request
   * @param $id
   * @return RedirectResponse
   * @throws ValidationException
   */
  public function update(Request $request, $id)
  {
    $this->validate($request, [
      'price' => 'required|numeric',
      'pin' => 'required|numeric',
    ]);

    $grade = Grade::find($id);
    if (strpos($request->price, ".") !== false || strpos($request->price, ",") !== false) {
      $rawBalance = str_replace(array(".", ","), "", $request->price);
      $rawBalance /= 100000000;
      $balance = number_format($rawBalance * 100000000, 0, '.', '');
    } else {
      $rawBalance = $request->price/100000000;
      $balance = number_format($rawBalance * 100000000, 0, '.', '');
    }
    $grade->price = $balance;
    $grade->pin = $request->pin;
    $grade->save();

    return redirect()->back();
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param $id
   * @return RedirectResponse
   */
  public function destroy($id)
  {
    Grade::destroy($id);
    return redirect()->back();
  }
}
