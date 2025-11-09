<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookController extends Controller
{

        public function index()
        {
            return view('books.create');
        }
        public function store(Request $request)
        {
            $file = $request->file;
            if($file) {
                try
                {
                    Excel::import(new BookImport, time(). '.xlsx');
                }
                catch(Exception $ex)
                {
                    dd($ex);
                }
            }
        }

    }