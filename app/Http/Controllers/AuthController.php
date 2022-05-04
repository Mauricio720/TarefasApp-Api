<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Conquest;
use Illuminate\Support\Facades\Hash;
use App\Models\Task;

class AuthController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api',['except'=>['login','add','unauthorized']]);
    }

    public function login(Request $request){
        $array=['error'=>''];
        $data=$request->only(['login','password','responseFacebook','responseGoogle']);
        $validation= Validator::make($data,[
                'login'=>['string','nullable'],
                'password'=>['string','nullable'],
            ]
        );

        if($validation->fails()){
            $array['error']=$validation->errors()->first();
        }else{
            if($request->filled('responseFacebook')){
                $responseFacebook=$request->input('responseFacebook');
                $token=$this->loginWithFacebook($responseFacebook);
               
                if($token){
                    $array['token']=$token;

                }else{
                    $array['token']=$token;
                    $array['error']="Algo deu errado FACEBOOK!";
                }   

                
            }else if($request->filled('responseGoogle')){    
                $responseGoogle=$request->input('responseGoogle');
                $token=$this->loginWithGoogle($responseGoogle);
               
                if($token){
                    $array['token']=$token;

                }else{
                    $array['token']=$token;
                    $array['error']="Algo deu errado GOOGLE!";
                }   
            }else{
                $errors=Validator::make($data,[
                    'login'=>'required',
                    'password'=>'required'
                ]);

                if($errors->fails()){
                    $array['error']=$errors->errors()->first();
                }else{
                    $token=Auth::attempt([
                        'login'=>$data['login'],
                        'password'=>$data['password']
                    ]);
    
                    if($token){
                        $array['token']=$token;
                    }else{
                        $array['error']="Email ou senha estão incorretos";
                    }
                }
            }
        }

        return $array;
    }


    private function loginWithFacebook($responseFacebook){
        $name=$responseFacebook['name'];
        $email=$responseFacebook['email'];
        $id=$responseFacebook['userID'];
        $token="";
        $userFacebook=User::where('idFacebook',$id)->first();
        if($userFacebook != null){
            $token=Auth::attempt([
                'id'=>$userFacebook->id,
                'name'=>$userFacebook->name,
                'email'=>$email,
                'password'=>$id
            ]);
        }else{
            
            $user=new User();
            $user->name=$name;
            $user->email=$email;
            $user->idFacebook=$id;
            $user->password=Hash::make($id);
            $user->save();

            $conquest=new Conquest();
            $conquest->idUser=$user->id;
            $conquest->save();

            $token=Auth::attempt([
                'id'=>$user->id,
                'idFacebook'=>$id,
                'name'=>$name,
                'email'=>$email,
                'password'=>$id
            ]);
        }

        return $token;
    }

    private function loginWithGoogle($responseGoogle){
        $name=$responseGoogle['profileObj']['givenName'];
        $lastName=$responseGoogle['profileObj']['familyName'];
        $email=$responseGoogle['profileObj']['email'];
        $id=$responseGoogle['profileObj']['googleId'];
        $token="";
        $userGoogle=User::where('idGoogle',$id)->first();
        if($userGoogle != null){
            $token=Auth::attempt([
                'id'=>$userGoogle->id,
                'name'=>$userGoogle->name,
                'lastName'=>$userGoogle->lastName,
                'email'=>$userGoogle->email,
                'password'=>$id
            ]);
        }else{
            $user=new User();
            $user->name=$name;
            $user->lastName=$lastName;
            $user->email=$email;
            $user->idGoogle=$id;
            $user->password=Hash::make($id);
            $user->save();

            $conquest=new Conquest();
            $conquest->idUser=$user->id;
            $conquest->save();

            $token=Auth::attempt([
            'idGoogle'=>$id,
            'name'=>$name,
            'lastName'=>$lastName,
            'email'=>$email,
            'password'=>$id
        ]);
    }

        return $token;
    }
    

    public function add(Request $request){
        $array=['error'=>''];
        $data=$request->only(['name','lastName','login','password','profileImg','email']);
        $errors=$this->validator($data);
        
        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            if($request->filled(['name','lastName','login','password','email'])){
                $user=User::where('email',$data['email'])->orwhere('login',$data['login'])->first();
                if($user == null){
                    $path_profileImg="";
                    $imgName="";
    
                    if($request->file('profileImg') && $request->file('profileImg')->isValid()){
                        $image=md5(rand(0,99999).rand(0,99999)).'.'.$request->file('profileImg')->getClientOriginalExtension();
                        $imgName=$image;
                        $path="/users/";
                        $request->file('profileImg')->storeAs($path,$image);
                        $path_profileImg=url('/')."/storage".$path."/".$image;
                    }
                    
                    $user=new User();
                    $user->name=$data['name'];
                    $user->lastName=$data['lastName'];
                    $user->login=$data['login'];
                    $user->password=Hash::make($data['password']);
                    $user->email=$data['email'];
                    $user->profileImg=$path_profileImg;
                    $user->imgName=$imgName;
                    $user->save();
    
                    $conquest=new Conquest();
                    $conquest->idUser=$user->id;
                    $conquest->save();
    
                    $token=Auth::attempt([
                        'login'=>$data['login'],
                        'password'=>$data['password']
                    ]);
                    
                    if($token){
                        $array['token']=$token;
                    }else{
                        $array['error']="Um erro inesperado ocorreu!";
                    }
                }else{
                    $array['error']="Email ou login ja estão sendo utilizados";
                }
            }
        }

        return $array;
    }


    public function getUser(){
        $array=['error'=>""];
        $array['user']=Auth::user();
        $array['taskSuccess']=Task::where('selected',true)->where('idUser',Auth::user()->id)->count();
        $array['totalConquest']=$this->getTotalNumberConquest();
        return $array;
    }


    private function getTotalNumberConquest(){
        $conquest=Conquest::where('idUser',Auth::user()->id)->first();
        $oneDay=$conquest->one_day;
        $twoDay=$conquest->two_day;
        $threeDay=$conquest->three_day;
        $fourDay=$conquest->four_day;
        $fiveDay=$conquest->five_day;
        $sixDay=$conquest->six_day;
        $oneWeek=$conquest->one_week;
        $twoWeek=$conquest->two_week;
        $threeWeek=$conquest->three_week;
        $oneMonth=$conquest->one_month;
        $twoMonth=$conquest->two_month;
        $threeMonth=$conquest->three_month;
        $fourMonth=$conquest->four_month;
        $fiveMonth=$conquest->five_month;
        $sixMonth=$conquest->six_month;
        $sevenMonth=$conquest->seven_month;
        $eightMonth=$conquest->eight_month;
        $nineMonth=$conquest->nine_month;
        $tenMonth=$conquest->ten_month;
        $elevenMonth=$conquest->eleven_month;
        $oneYear=$conquest->one_year;

        $total=$oneDay+$twoDay+$threeDay+$fourDay+$fiveDay+$sixDay+$oneWeek+$twoWeek+$threeWeek+$twoMonth+
        $oneMonth+$twoMonth+$threeMonth+$fourMonth+$fiveMonth+$sixMonth+$sevenMonth+$eightMonth+$nineMonth
        +$tenMonth+$elevenMonth+$oneYear;

        return $total;

    }
    
    public function logout(){
        Auth::logout();
        return ['error'=>""];
    }

    public function refresh(){
        $token=Auth::refresh();
        return ['error'=>"",'token'=>$token];
    }

    public function unauthorized(){
        return response()->json(['error'=>"Não autorizado"],401);
    }
    
    private function validator($data){
        $correct_names = [
            'name'=>'nome',
            'lastName'=>'sobrenome',
            'password'=>'senha'
        ];
        return Validator::make($data,[
            'name'=>['required','string','max:50'],
            'lastName'=>['required','string','max:50'],
            'login'=>['required','string','max:50'],
            'password'=>['required','string','max:150'],
            'email'=>['required','email','required','string','max:400'],
        ],[],$correct_names);
    }
}
