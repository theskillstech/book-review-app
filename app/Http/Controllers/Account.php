<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\File;

class Account extends Controller
{
    // This method will show register page
    public function register(){
        return view('account.register');
    }
     // This method will register a user
    public function processRegister(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=> 'required|min:3',
            'email'=> 'required|email|unique:users',
            'password'=> 'required|confirmed|min:5',
            'password_confirmation' => 'required',
        ]);
        if($validator->fails()){
            return redirect()->route('account.register')->withInput()->withErrors($validator);
        }
        // Now Register User
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        return redirect()->route('account.login')->with('success','You have register successfully');
 
    }
    public function login(){
        return view('account.login');
    }
    public function authenticate(Request $request){
        $validator = Validator::make($request->all(),[
            'email'=> 'required|email',
            'password'=>'required'
        ]);
        if($validator->fails()){
            return redirect()->route('account.login')->withInput()->withErrors($validator);
        }
        if(Auth::attempt(['email'=>$request->email, 'password'=>$request->password])){
            return redirect()->route('account.profile');
        }else{
            return redirect()->route('account.login')->with('error','Either Email or Password Incorrect');
        }
      
    }
   // This Method wil show user profile Page
    public function profile(){
        $user = User::find(Auth::user()->id);
        // dd($user);
        return view('account.profile',['user'=>$user]);
    }
    
    // This Method wil Update user profile
    public function updateprofile(Request $request){
        $rules = [
            'name'=>'required|min:3',
            'email'=>'required|email|unique:users,email,'.Auth::user()->id.',id',
        ];
        if(!empty($request->image))
        {
            $rules['image'] = 'image'; 
        }
        $validator=Validator::make($request->all(),$rules);
        if($validator->fails()){
            return redirect()->route('account.profile')->withInput()->withErrors($validator);
        }
        $user = User::find(Auth::user()->id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
      // Here will be the image 
      if(!empty($request->image)){
        // delete old image here

        File::delete(public_path('uploads/profile/'.$user->image));
        File::delete(public_path('uploads/profile/thumb/'.$user->image));

        $image = $request->image;
        $ext = $image->getClientOriginalExtension();
        $imageName = time().'.'.$ext;
        $image->move(public_path('uploads/profile'),$imageName);
        $user->image = $imageName;
        $user->save();
        // create new image instance
        $manager = new ImageManager(Driver::class);
        $img = $manager->read(public_path('uploads/profile/'.$imageName)); // 800 x 600
        $img->cover(150, 150);
        $img->save(public_path('uploads/profile/thumb/'.$imageName));

    }
     

      return redirect()->route('account.profile')->with('success','Profile Updated Successfully');

    }
    public function logout(){
        Auth::logout();
        return redirect()->route('account.login');
    }
}
