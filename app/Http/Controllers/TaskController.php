<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskRepeat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Conquest;
use App\Models\User;

class TaskController extends Controller
{
    public $conquestArray=[
        '1'=>'one_day',
        '2'=>'two_day',
        '3'=>'three_day',
        '4'=>'four_day',
        '5'=>'five_day',
        '6'=>'six_day',
        '7'=>'one_week',
        '14'=>'two_week',
        '21'=>'three_week',
        '30'=>'one_month',
        '60'=>'two_month',
        '90'=>'three_month',
        '120'=>'four_month',
        '150'=>'five_month',
        '180'=>'six_month',
        '210'=>'seven_month',
        '240'=>'eight_month',
        '270'=>'nine_month',
        '300'=>'ten_month',
        '330'=>'eleven_month',
        '360'=>'one_year',
    ];
    
    public function __construct(){
        $this->middleware('auth:api');
    }

    private function verifySequence(){
        $sequence_verify=$this->verifyAllTaskComplete(date('Y-m-d',strtotime('-1 day')));
        if($sequence_verify==false){
            $user=User::where('id',Auth::user()->id)->first();
            $user->sequence=0;
            $user->save();

            $conquest=Conquest::where('idUser',Auth::user()->id)->first();
            $conquest->sequence_zero=true;
            $conquest->save();
        }
    }

    public function getTask($idUser,Request $request){
        $this->verifyAllRepeatTask();
        $this->verifySequence();

        $data=$request->only('filterTitle','filterType','filterDate');
        $array=['error'=>"",'isMe'=>true];
        $isMe=false;

        if($idUser == Auth::user()->id){
            $isMe=true;
            $query=Task::query()->where('idUser',$idUser);;
            
            if($request->filled('filterTitle')){
                $query->where('title','LIKE',"%".$data['filterTitle']."%");
            }
            
            if($request->filled('filterType') && $data['filterType'] > 0){
                $type=$data['filterType'];
                if($type <=3){
                    $query->where('importance',$type);
                }else{
                    if($type==4){
                        $query->where('selected',1);
                    }else{
                        $query->where('selected',0);
                    }
                }
            }

            if($request->filled('filterDate')){
                $date=$data['filterDate'];
                $query->where('date',$date);
            }else{
                $query->where('date',date('Y-m-d'));
            }
            
            $array['data']=$query->get();
        }

        $array['isMe']=$isMe;
        $array['tasks']=$this->getRepeatInformation($array['data']);
        
        return $array;
    }

    private function getRepeatInformation($allTasks){
        $allTask=[];
        foreach ($allTasks as $task) {
            $taskInfo=$task;
            $idTask=$task->idTaskRepeat;
            $taskRepeat=TaskRepeat::where('id_task',$idTask)->first();
            $taskInfo['infoRepeat']=$taskRepeat;
            $allTask[]=$taskInfo;
        }
    }

    private function verifyAllRepeatTask(){
        $allTasksRepeat=TaskRepeat::all();
        foreach ($allTasksRepeat as $taskRepeat) {
            $idTask=$taskRepeat->id_task;
            $dateRegister=$taskRepeat->last_register;
            $everyDay=$taskRepeat->everyday;
            $today=date('Y-m-d');
            $task=Task::where('id',$idTask)->first();
            
            if($everyDay && strtotime($dateRegister) != strtotime($today)){
                $this->insertTaskRepeat($task);
            }

            $weekDayNumber = date('w', strtotime($today));
            $sunday=$taskRepeat->sunday;
            $monday=$taskRepeat->monday;
            $tuesday=$taskRepeat->tuesday;
            $wednesday=$taskRepeat->wednesday;
            $thursday=$taskRepeat->thursday;
            $friday=$taskRepeat->friday;
            $saturday=$taskRepeat->saturday;

            if($weekDayNumber==0 && $sunday && strtotime($dateRegister) != strtotime($today)){
                $this->insertTaskRepeat($task);
            }

            if($weekDayNumber==1 && $monday && strtotime($dateRegister) != strtotime($today)){
                $this->insertTaskRepeat($task);
            }

            if($weekDayNumber==2 && $tuesday && strtotime($dateRegister) != strtotime($today)){
                $this->insertTaskRepeat($task);
            }

            if($weekDayNumber==3 && $wednesday && strtotime($dateRegister) != strtotime($today)){
                $this->insertTaskRepeat($task);
            }

            if($weekDayNumber==4 && $thursday && strtotime($dateRegister) != strtotime($today)){
                $this->insertTaskRepeat($task);
            }

            if($weekDayNumber==5 && $friday && strtotime($dateRegister) != strtotime($today)){
                $this->insertTaskRepeat($task);
            }

            if($weekDayNumber==6 && $saturday && strtotime($dateRegister) != strtotime($today)){
                $this->insertTaskRepeat($task);
            }

            $taskRepeat=TaskRepeat::where('id',$taskRepeat->id)->first();
            $taskRepeat->last_register=date('Y-m-d');
            $taskRepeat->save();
        }
    }

    private function insertTaskRepeat($task){
        $taskRegister=new Task();
        $taskRegister->title=$task->title;
        $taskRegister->start=$task->start;
        $taskRegister->end=$task->end;
        $taskRegister->date=date('Y-m-d');
        $taskRegister->importance=$task->importance;
        $taskRegister->description=$task->description;
        $taskRegister->icon=$task->icon;
        $taskRegister->idUser=$task->idUser;
        $taskRegister->selected=$task->selected;
        $taskRegister->task_path=$task->task_path;
        $taskRegister->task_img=$task->task_img;
        $taskRegister->idTaskRepeat=$task->idTaskRepeat;
        $taskRegister->save();
    }

    public function add(Request $request,$idUser){
        $array=['error'=>"",'isMe'=>true];
        $data=$request->only(['title','start','end','date','importance','description','icon']);
        $errors=$this->validator($data);
        $isMe=false;

        if($idUser == Auth::user()->id){
            $isMe=true;

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        
        }else{
            if($request->filled(['title','start','end','date','importance','description'])){
                $imgName="";
                $taskImg_path="";
                if($request->file('taskImgFile') && $request->file('taskImgFile')->isValid()){
                    $image=md5(rand(0,99999).rand(0,99999)).'.'.$request->file('taskImgFile')->getClientOriginalExtension();
                    $imgName=$image;
                    $path="/tasks/";
                    $request->file('taskImgFile')->storeAs($path,$image);
                    $taskImg_path=url('/')."/storage".$path.$image;
                }

                $wasComplete=$this->verifyAllTaskComplete($data['date']);

                $task=new Task();
                $task->title=$data['title'];
                $task->start=$data['start'];
                $task->end=$data['end'];
                $task->date=$data['date'];
                $task->importance=$data['importance'];
                $task->description=$data['description'];
                $task->idUser=$idUser;
                $task->task_path=$taskImg_path;
                $task->task_img=$imgName;
                $task->save();
                
                
                if($wasComplete){
                    $sequence=Auth::user()->sequence;
                    if($sequence==0){
                        $sequence=1;
                    }
                    $conquest=Conquest::where('idUser',$idUser)->first();
                    $conquest_day=$this->conquestArray[$sequence];
                    $conquest->$conquest_day=$conquest->$conquest_day-1;
                    $conquest->save();

                    $user=User::where('id',Auth::user()->id)->first();
                    $user->sequence=$sequence-1;  
                    $user->save();
                }
            }
        }
    }

        $array['isMe']=$isMe;
        return $array;
    
    }

    public function update(Request $request,$idUser){
        $array=['error'=>"",'isMe'=>true];
        $data=$request->only(['id','title','start','end','date','importance','description','icon']);
        $errors=$this->validator($data,true);
        $isMe=false;

        if($idUser == Auth::user()->id){
            $isMe=true;

        if($errors->fails()){
            $array['error']=$errors->errors()->first();
        
        }else{
            if($request->filled(['id','title','start','end','importance','description'])){
                $task=Task::where('id',$data['id'])->first();
                
                $imgName=$task->task_img;
                $taskImg_path=$task->task_path;
                if($request->file('taskImgFile') && $request->file('taskImgFile')->isValid()){
                    $image=md5(rand(0,99999).rand(0,99999)).'.'.$request->file('taskImgFile')->getClientOriginalExtension();
                    $imgName=$image;
                    $path="/tasks/";
                    $request->file('taskImgFile')->storeAs($path,$image);
                    $taskImg_path=url('/')."/storage".$path.$image;
                }
               
                $task->title=$data['title'];
                $task->start=$data['start'];
                $task->end=$data['end'];
                $task->importance=$data['importance'];
                $task->description=$data['description'];
                $task->task_path=$taskImg_path;
                $task->task_img=$imgName;
                $task->save();
            }
        }
    }

    $array['isMe']=$isMe;
    return $array;
    
    }

    public function changeSelectTask($id,$idUser){
        $array=['error'=>"",'isMe'=>true];
        $isMe=false;
        if($idUser==Auth::user()->id){
            $isMe=true;
            if($id!=""){
                $task=Task::where('id',$id)->first();
                $selected=$task->selected;
                
                if($selected==0){
                    $selected=1;
                }else{
                    $selected=0;
                }
                $task->selected=$selected;
                $task->save();
            } 
        }

        $array['isMe']=$isMe;
        $array['successToday']=$this->verifyAllTaskComplete();
        $array['msg']=$this->verifyConquest();
        return $array;
    }

    private function verifyAllTaskComplete($dateChoice=null){
        $verify=false;
        $idUser=Auth::user()->id;
        $date=$dateChoice==null?date('Y-m-d'):$dateChoice;
        $allTaskNumber=Task::where('idUser',$idUser)->where('date',$date)->count();
        $taskSuccessNumber=Task::where('idUser',$idUser)->where('date',$date)->where('selected',1)->count();
        
        if($allTaskNumber==0){
            $verify=true;
        }

        if($allTaskNumber>0){
            if($allTaskNumber==$taskSuccessNumber){
                $verify=true;
            }
        }
        
        return $verify;
    }

    private function verifyConquest(){
        $user=User::where('id',Auth::user()->id)->first();
        $sequenceNumber=$user->sequence;
        $conquest=Conquest::where('idUser',$user->id)->first();

        if($this->verifyAllTaskComplete()){
            $user->sequence=$sequenceNumber+1;
            $user->save();
            $conquest->already_decrease_sequence=false;
        }else{
            if($user->sequence>0 && $conquest->already_decrease_sequence==false){
                $user->sequence=$sequenceNumber-1;  
                $user->save();
                $conquest->already_decrease_sequence=true;
            }
        }

        $sequence=strval($user->sequence);
        
        $messages=[
            '1'=>'Você completou uma sequência de 1 dia!!!',
            '2'=>'Você completou uma sequência de 2 dias!!!',
            '3'=>'Você completou uma sequência de 3 dias!!!',
            '4'=>'Você completou uma sequência de 4 dias!!!',
            '5'=>'Você completou uma sequência de 5 dias!!!',
            '6'=>'Você completou uma sequência de 6 dias!!!',
            '7'=>'Você completou uma sequência de uma semana!!!',
            '14'=>'Você completou uma sequência de duas semanas!!!',
            '21'=>'Você completou uma sequência de três semanas!!!',
            '30'=>'Você completou uma sequência de um mês!!!',
            '60'=>'Você completou uma sequência de dois meses!!!',
            '90'=>'Você completou uma sequência de três meses!!!',
            '120'=>'Você completou uma sequência de quatro meses!!!',
            '150'=>'Você completou uma sequência de cinco meses!!!',
            '180'=>'Você completou uma sequência de seis meses!!!',
            '210'=>'Você completou uma sequência de sete meses!!!',
            '240'=>'Você completou uma sequência de oito meses!!!',
            '270'=>'Você completou uma sequência de nove meses!!!',
            '300'=>'Você completou uma sequência de dez meses!!!',
            '330'=>'Você completou uma sequência de onze meses!!!',
            '360'=>'Você completou uma sequência de 1 ano!!!',
        ];

        $message="Voce concluiu as tarefas de hoje!!!";
        if(array_key_exists($sequence, $this->conquestArray)){
            $conquest_day=$this->conquestArray[$sequence];
            
            if($this->verifyAllTaskComplete()){
                $conquest->$conquest_day=$conquest->$conquest_day+1;
                $conquest->sequence_zero=false;
                $message=$messages[$sequence];
                $conquest->already_decrease=false;
            }else{
                $oldSequence=$sequence+1;
                if(array_key_exists($oldSequence, $this->conquestArray) && $conquest->already_decrease==false){
                    $conquest_day=$this->conquestArray[$oldSequence];
                    $conquest->$conquest_day=$conquest->$conquest_day-1;
                    $conquest->already_decrease=true;
                }
            }
        }

        if(!$this->verifyAllTaskComplete() && $conquest->already_decrease==false){
            $oldSequence=$sequence+1;
            if(array_key_exists($oldSequence, $this->conquestArray)){
                $conquest_day=$this->conquestArray[$oldSequence];
                $conquest->$conquest_day=$conquest->$conquest_day-1;
                $conquest->already_decrease=true;
            }
        }

        $conquest->save();
       
        return $message;
    }

    public function delete($id,$idUser){
        $array=['error'=>"",'isMe'=>true];
        $isMe=false;
        if($idUser==Auth::user()->id){
            $isMe=true;
            if($id!=""){
                $task=Task::where('id',$id)->first();
                $oldDate=$task->date;
                if($task !=null && $task->idUser==Auth::user()->id){
                    $wasComplete=$this->verifyAllTaskComplete($oldDate);
                    Task::where('id',$id)->delete();
                   
                    if($wasComplete===false){
                        $isCompleteNow=$this->verifyAllTaskComplete($oldDate);
                        if($isCompleteNow){
                            $user=User::where('id',Auth::user()->id)->first();
                            $user->sequence=$user->sequence+1;  
                            $user->save();

                            $conquest=Conquest::where('idUser',Auth::user()->id)->first();
                            $conquest_day=$this->conquestArray[$user->sequence];
                            $conquest->$conquest_day=$conquest->$conquest_day+1;
                            $conquest->save();
                        }
                    }

                    $allTaskNumber=Task::where('idUser',$idUser)->where('date',$oldDate)->count();
                    if($allTaskNumber==0){
                        $user=User::where('id',Auth::user()->id)->first();
                        if($user->sequence > 0){
                            $conquest=Conquest::where('idUser',Auth::user()->id)->first();
                            $conquest_day=$this->conquestArray[$user->sequence];
                            $conquest->$conquest_day=$conquest->$conquest_day-1;
                            $conquest->save();

                            $user->sequence=$user->sequence-1;  
                            $user->save();
                        }
                    }
                }else{
                    $array['error']="Ocorreu um erro!";
                }
            }
        }

        $array['isMe']=$isMe;
        return $array;
    }

   
    public function addTaskRepeat(Request $request,$idUser){
        $array=['error'=>"",'isMe'];
        $isMe=false;
        
        if($idUser==Auth::user()->id){
            $isMe=true;
            $data=$request->only(['id','daysRepeat']);
            $array['data']=$data;
            $idTask=$data['id'];
            $daysSelected=explode(',',$data['daysRepeat']);
            
            $task=Task::where('id',$idTask)->first();
            $idTaskRepeat=$task->idTaskRepeat;
            
            if($request->filled('daysRepeat')){
                if(in_array("7",$daysSelected)){
                    $taskRepeat="";
                    if($idTaskRepeat=="" || $idTaskRepeat==0){
                        $taskRepeat=new TaskRepeat();
                        $task->idTaskRepeat=$idTask;
                        $taskRepeat->last_register=date('Y-m-d');
                        $task->save();
                    }else{
                        $taskRepeat=TaskRepeat::where('id_task',$idTaskRepeat)->first();
                    }
                    
                    $taskRepeat->id_task=$idTask;
                    $taskRepeat->everyday=true;
                    $taskRepeat->sunday=false;
                    $taskRepeat->monday=false;
                    $taskRepeat->tuesday=false;
                    $taskRepeat->wednesday=false;
                    $taskRepeat->thursday=false;
                    $taskRepeat->friday=false;
                    $taskRepeat->saturday=false;
                    $taskRepeat->save();

                }else{
                    $array['daysSelected']=$daysSelected;
                    $sunday=in_array('0',$daysSelected)?true:false;
                    $monday=in_array('1',$daysSelected)?true:false;
                    $tuesday=in_array('2',$daysSelected)?true:false;
                    $wednesday=in_array('3',$daysSelected)?true:false;
                    $thursday=in_array('4',$daysSelected)?true:false;
                    $friday=in_array('5',$daysSelected)?true:false;
                    $saturday=in_array('6',$daysSelected)?true:false;

                    $taskRepeat="";
                    if($idTaskRepeat=="" || $idTaskRepeat==0){
                        $taskRepeat=new TaskRepeat();
                        $taskRepeat->last_register=date('Y-m-d');
                    }else{
                        $taskRepeat=TaskRepeat::where('id_task',$idTaskRepeat)->first();
                    }
                    $taskRepeat->id_task=$idTask;
                    $taskRepeat->everyday=false;
                    $taskRepeat->sunday=$sunday;
                    $taskRepeat->monday=$monday;
                    $taskRepeat->tuesday=$tuesday;
                    $taskRepeat->wednesday=$wednesday;
                    $taskRepeat->thursday=$thursday;
                    $taskRepeat->friday=$friday;
                    $taskRepeat->saturday=$saturday;
                    $taskRepeat->save();
                }
            }else{
                if($idTaskRepeat != "" || $idTaskRepeat != 0){
                    $taskRepeat=TaskRepeat::where('id_task',$idTaskRepeat)->first();
                    $taskRepeat->delete();

                    $task->idTaskRepeat=0;
                    $task->save();
                }
            }
        }

        $array['isMe']=$isMe;

        return $array;
    }

    public function getAllConquest($idUser){
        $array=['error'=>"",'isMe'=>true];
        $isMe=false;

        if($idUser == Auth::user()->id){
            $array['data']=Conquest::where('idUser',Auth::user()->id)->get();
            $array['isMe']=$isMe;
        }

        return $array;
    }

    private function validator($data,$edit=false){
        $ruleDate=['required','date'];
        $correct_names = [
            'titulo'=>'titulo',
            'start'=>'inicio',
            'end'=>'termino',
            'date'=>'data',
            'importance'=>'importancia',
            'description'=>'descrição',
        ];
        if($edit){
            $ruleDate=['date'];
        }
        return Validator::make($data,[
            'title'=>['required','string','max:20'],
            'start'=>['required'],
            'end'=>['required'],
            'date'=>$ruleDate,
            'importance'=>['required','int'],
            'description'=>['required','string'],
        ],[],$correct_names);
    }
}
