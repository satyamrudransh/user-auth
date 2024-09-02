<?php

namespace App\Http\Controllers;
use App\Models\Contacts;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\ContactsResource;


class ContactsController extends Controller
{
    public function index(Request $request)
    {
        try{
            if($request->has('limit')){
                return ContactsResource::collection(Contacts::paginate($request->limit));
            }
            if($request ->has('search')){
                return ContactsResource::collection(Contacts::search($request -> search)->get());
            }
    
            else{
                return ContactsResource::collection(Contacts::all());
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
            return new ContactsResource(Contacts::findOrFail($id));
        }
        catch(\Exception $e){
            return $e->getMessage();
 
        }
    }

    
    public function store(Request $request)
    {
        $contacts = new Contacts();

        if($request->has('firstName'))
        $contacts['firstName'] = $request->input('firstName');

        if($request->has('lastName'))
        $contacts['lastName'] = $request->input('lastName');

        if($request->has('email'))
        $contacts['email'] = $request->input('email');

        if($request->has('avatar'))
        $contacts['avatar'] = $request->input('avatar');
        

        $contacts->save();

        $success['0']['code']='0001';
        $success['0']['status']='200';
        $success['0']['title']='Submitted successfully';
        $success['0']['detail']='Contacts placed successfully';
        return response()->json(['data', $success],'200');
    }


    public function update(Request $request , $id)
    {
        $contacts = Contacts::findOrFail($id);
     
        if($request->has('firstName'))
        $contacts['firstName'] = $request->input('firstName');

        if($request->has('lastName'))
        $contacts['lastName'] = $request->input('lastName');

        if($request->has('email'))
        $contacts['email'] = $request->input('email');

        if($request->has('avatar'))
        $contacts['avatar'] = $request->input('avatar');

        $contacts->save();

        $success['0']['code']='0001';
        $success['0']['status']='200';
        $success['0']['title']='Updated successfully';
        $success['0']['detail']='Contacts Update successfully';
        return response()->json(['data' , $success],'200');

    }


    public function delete($id)
    {
        $contacts = Contacts::find($id);
        $contacts->delete();

        $success['0']['code']='0001';
        $success['0']['status']='200';
        $success['0']['title']='Deleted successfully';
        $success['0']['detail']='Contacts Delete successfully';
        return response()->json(['data' ,  $success],'200');
    }
}
