<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
   private  $urlFront="https://mauriciodev.online/remember_password/";    
    
    public function __construct(){
        $this->middleware('auth:api',['except'=>['forgotPassword',
            'verifyTokenRememberPass','changePass']]);
    }

    public function updateUser(Request $request){
        $array=['error'=>''];
        $data=$request->only(['name','lastName','login','password','profileImg','email']);

        $errors=$this->validator($data);
        
        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        }else{
            if($request->filled(['name','lastName','login','email'])){
                $user=User::where('id',Auth::user()->id)->first();
                $path_profileImg=$user->profileImg;
                $imgName=$user->imgName;
                
                if($request->file('profileImg') && $request->file('profileImg')->isValid()){
                    Storage::delete('users/'.$user->imgName);
                    $image=md5(rand(0,99999).rand(0,99999)).'.'.$request->file('profileImg')->getClientOriginalExtension();
                    $imgName=$image;
                    $path="/users/";
                    $request->file('profileImg')->storeAs($path,$image);
                    $path_profileImg=url('/')."/storage".$path.$image;
                }
                
                $user->name=$data['name'];
                $user->lastName=$data['lastName'];
                $user->login=$data['login'];
                if($request->filled('password')){
                    $user->password=Hash::make($data['password']);
                }
                $user->email=$data['email'];
                $user->profileImg=$path_profileImg;
                $user->imgName=$imgName;
                $user->save();
                $array['user']=$user;
            }
        }

        return $array;
    }

    public function forgotPassword(Request $request){
        $array=['error'];
        $data=$request->only('email');
        
        if($request->filled('email')){
            $email=$data['email'];
            $user=User::where('email',$email)->first();
            if($user != null){
                $tokenPasswordRemember=Hash::make($data['email']);
                $tokenPasswordRemember=str_replace("/",'.',$tokenPasswordRemember);
                $user->passRememberToken=$tokenPasswordRemember;
                $user->save();

                $this->sendEmail($user);
            }else{
                $array['error']="Email não encontrado!";
            }
        }else{
            $array['error']="Email é obrigatório!";
        }

        return $array;
    }

    private function sendEmail($user){
        $email="mauriciodev@mauriciodev.online";
        $assunto=utf8_decode("Mudança de senha!");
        $link=$this->urlFront.$user->passRememberToken;
        $corpo=utf8_decode("Ola ".$user->name." segue o link para mudança de senha."."\r\n").
        $link;
        $cabecalho="From:".$email."\r\n".
                    "Reply-To: ".$email."\r\n".
                    "X-Mailer: PHP/".phpversion();
        mail($user->email, $assunto,$corpo,$cabecalho);
    }

    public function verifyTokenRememberPass(Request $request){
        $array=['there_is'=>false];
        $data=$request->only('tokenPassword');

        if($request->filled('tokenPassword')){
            $user=User::where('passRememberToken',$data['tokenPassword'])->first();
            if($user != null){
                $array['there_is']=true;
            }
        }

        return $array;
    }

    public function changePass(Request $request){
        $array=['error'=>""];
        $data=$request->only(['newPass','tokenPassword']);

        if($request->filled('newPass')){
            $user=User::where('passRememberToken',$data['tokenPassword'])->first();
            if($user == null){
                $array['error']="Não foi possivel alterar sua senha";
            }else{
                $user->password=Hash::make($data['newPass']);    
                $user->passRememberToken="";
                $user->save();
            }
        }else{
            $array['error']="A nova senha é obrigatória!!!";
        }

        return $array;
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
            'password'=>['string','max:150','nullable'],
            'email'=>['required','email','string','max:400'],
        ],[],$correct_names);
    }
}
