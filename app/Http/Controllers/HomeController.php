<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
class HomeController extends Controller
{
    // this method will show home page
    public function index(Request $request){
        $books = Book::orderBy('created_at','DESC');
        if(!empty($request->keyword)){
            $books->where('title','like','%'.$request->keyword.'%');
        }
      $books = $books->where('status',1)->paginate(8);
        return view('home',[
            'books'=>$books
        ]);
    }
}
