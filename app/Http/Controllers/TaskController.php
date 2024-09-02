<?php

namespace App\Http\Controllers;
use App\Models\Task;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\TaskResource;


class TaskController extends Controller
{
    public function index(Request $request)
    {
        try{
            if($request->has('limit')){
                return TaskResource::collection(Task::paginate($request->limit));
            }
            if($request ->has('search')){
                return TaskResource::collection(Task::search($request -> search)->get());
            }
    
            else{
                return TaskResource::collection(Task::all());
            } 
        }
        catch(\Exception $e)
        {
            return $e->getMessage();
             
        }
       
    
    }

    public function show($id)
    {
        try{
            return new TaskResource(Task::findOrFail($id));
        }
        catch(\Exception $e){
            return $e->getMessage();
 
        }
    }

    
    public function store(Request $request)
    {
        $task = new Task();

        if($request->has('firstName'))
        $task['firstName'] = $request->input('firstName');

        if($request->has('lastName'))
        $task['lastName'] = $request->input('lastName');

        if($request->has('email'))
        $task['email'] = $request->input('email');

        if($request->has('avatar'))
        $task['avatar'] = $request->input('avatar');
        

        $task->save();

        $success['0']['code']='0001';
        $success['0']['status']='200';
        $success['0']['title']='Submitted successfully';
        $success['0']['detail']='Task placed successfully';
        return response()->json(['data', $success],'200');
    }


    public function update(Request $request , $id)
    {
        $task = Task::findOrFail($id);
     
        if($request->has('firstName'))
        $task['firstName'] = $request->input('firstName');

        if($request->has('lastName'))
        $task['lastName'] = $request->input('lastName');

        if($request->has('email'))
        $task['email'] = $request->input('email');

        if($request->has('avatar'))
        $task['avatar'] = $request->input('avatar');

        $task->save();

        $success['0']['code']='0001';
        $success['0']['status']='200';
        $success['0']['title']='Updated successfully';
        $success['0']['detail']='Task Update successfully';
        return response()->json(['data' , $success],'200');

    }


    public function delete($id)
    {
        $task = Task::find($id);
        $task->delete();

        $success['0']['code']='0001';
        $success['0']['status']='200';
        $success['0']['title']='Deleted successfully';
        $success['0']['detail']='Task Delete successfully';
        return response()->json(['data' ,  $success],'200');
    }
}
