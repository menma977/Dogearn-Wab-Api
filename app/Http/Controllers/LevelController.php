<?php

namespace App\Http\Controllers;

use App\Model\Grade;
use App\Model\Level;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class LevelController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return Application|Factory|View|void
   */
  public function index()
  {
    $level = Level::all();

    $data = [
      'level' => $level
    ];

    return view('level.index', $data);
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
      'percent' => 'required|numeric',
    ]);

    $level = new Level();
    $level->percent = str_replace(array(".", ","), ".", $request->percent);
    $level->save();

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
      'percent' => 'required|numeric',
    ]);

    $level = Level::find($id);
    $level->percent = str_replace(array(".", ","), ".", $request->percent);
    $level->save();

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
    Level::destroy($id);
    return redirect()->back();
  }
}
