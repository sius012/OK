<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountSetting extends Controller
{
   public function index(){
       $id = auth()->user()->id;
       $data = DB::table('users')->join("model_has_roles","model_has_roles.model_id","=","users.id")->join("roles","model_has_roles.role_id","=","roles.id")->select("users.*","roles.name as rn")->where("users.id",$id)->first();


       return view("profile",['data'=>$data]);
   }

   public function update(Request $req){
       $getpp = DB::table('users')->where('id',auth()->user()->id)->pluck("pp")->first();

       if($req->file("pp") != null){
        $file = $req->file('pp');
        $file->move("assets/pp/",$req->nama."-".date('mdy')."-profile.png");
      //  unlink(public_path('assets/pp/'.$getpp));
        DB::table('users')->where('id',auth()->user()->id)->update(['pp'=>$req->nama."-".date('mdy')."-profile.png"]);
      //  dd('hai');
       }

       $counting = DB::table('users')->where('email',$req->email)->count();
       DB::table('users')->where('id',auth()->user()->id)->update(['name'=>$req->nama]);
       if($counting > 0){
        return redirect()->to('accountsetting');
        
       }else{
        DB::table('users')->where('id',auth()->user()->id)->update(['email'=>$req->email]);
       }
     

       return back();
       
   }
}
