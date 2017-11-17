<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest',[
            'only'=>['create']
        ]);
    }
    //
    public function create()
    {
        return view('sessions.create');
    }

    //
    public function store(Request $request)
    {
        $this->validate($request,[
            'email'=>'required|email|max:255',
            'password'=>'required',
        ]);
        $credential = [
            'email'=>$request->email,
            'password'=>$request->password,
        ];
        if(Auth::attempt($credential,$request->has('remember'))){
            if(Auth::user()->activated){
                session()->flash('success','欢迎回来');
                return redirect()->intended(route('users.show',[Auth::user()]));
            } else{
                Auth::logout();
                session()->flash('warning','您的账号还未激活');
                return redirect('/');
            }


        }else{
            session()->flash('danger','您的邮箱和密码不匹配');
            return redirect()->back();
        }

    }

    //
    public function destory()
    {
        Auth::logout();
        session()->flash('success','退出成功');
        return redirect('login');
    }
}
