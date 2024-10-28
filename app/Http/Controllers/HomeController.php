<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Estudiante;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        if (Auth::user()) {
            $estudiantes = Estudiante::count();
            $profesores = User::where('id_rol', 4)->count();
            $cursos = Curso::count();
            return view('home', compact('estudiantes', 'profesores', 'cursos'));
        } else {
            Auth::logout();
            return redirect()->back();
        }
    }
}
