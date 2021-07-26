<?php

namespace App\Http\Controllers;

use App\Models\Objective;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ObjectiveController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api');
    }

    public function getObjective($idUser){
        $array=['error'=>"",'isMe'=>true];
        $isMe=false;

        if($idUser == Auth::user()->id){
            $isMe=true;
            $objective=Objective::where('id_user',$idUser)->get();
            $array['data']=$objective;
        }

        $array['isMe']=$isMe;
        return $array;
    }

    public function add(Request $request,$idUser){
        $array=['error'=>""];
        $data=$request->only(['title','type','level','idUser']);

        $errors=$this->validator($data);
        $isMe=false;

        if($idUser == Auth::user()->id){
            $isMe=true;
            
            if($errors->fails()){
                $array['error']=$errors->errors()->first();
            }else{
                if($request->filled(['title','type','level'])){
                    $objective=new Objective();
                    $objective->title=$data['title'];
                    $objective->type=$data['type'];
                    $objective->level=$data['level'];
                    $objective->id_user=$data['idUser'];
                    $objective->save();
                }
            }
        }
        
        $array['isMe']=$isMe;

        return $array;
    }

    public function update(Request $request,$idUser){
        $array=['error'=>""];
        $data=$request->only(['id','title','type','level','idUser']);

        $errors=$this->validator($data);
        $isMe=false;

        if($idUser == Auth::user()->id){
            $isMe=true;
            
            if($errors->fails()){
                $array['error']=$errors->errors()->first();
            }else{
                if($request->filled(['title','type','level'])){
                    $objective=Objective::where('id',$data['id'])->first();
                    $objective->title=$data['title'];
                    $objective->type=$data['type'];
                    $objective->level=$data['level'];
                    $objective->id_user=$data['idUser'];
                    $objective->save();
                }
            }
        }
        
        $array['isMe']=$isMe;

        return $array;
    }

    public function delete(Request $request){
        $array=['error'=>""];
        $data=$request->only(['id']);

        if($request->filled(['id'])){
            $objective=Objective::where('id',$data['id'])->first();
            $objective->delete();
        }

        return $array;

    }

    public function changeSelectedObjective($id,$idUser){
        $array=['error'=>"",'isMe'=>true];
        $isMe=false;
        if($idUser==Auth::user()->id){
            $isMe=true;
            if($id!=""){
                $objective=Objective::where('id',$id)->first();
                $done=$objective->done;
                
                if($done==0){
                    $done=1;
                }else{
                    $done=0;
                }
                $objective->done=$done;
                $objective->save();
            } 
        }

        $array['isMe']=$isMe;
       
        return $array;
    }


    private function validator($data){
        $correct_names = [
            'title'=>'objetivo',
            'type'=>'tipo',
            'level'=>'nivel'
        ];
        return Validator::make($data,[
            'title'=>['required','string','max:450'],
            'type'=>['required','int'],
            'level'=>['required','int'],
        ],[],$correct_names);
    }

}
