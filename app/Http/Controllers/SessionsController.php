<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    public function create()
    {
        return view('sessions.create');
    }

    public function store(Request $request)
    {
        $validate = $this->validate($request, [
            'email' => 'required|email|max:255',
            'password' => 'required|min:6'
        ]);

        if (Auth::attempt($validate, $request->has('remeber'))) {
            if(Auth::user()->activated){
                session()->flash('success', '登录成功');
                return redirect()->route('users.show', [Auth::user()]);
            } else {
                Auth::logout();
                session()->flash('success', '请激活后在登录');
                return redirect()->route('login');
            }
            
        } else {
            session()->flash('danger', '登录失败');
            return redirect()->back();
        }
    }

    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '退出成功');
        return redirect('login');
    }
}
