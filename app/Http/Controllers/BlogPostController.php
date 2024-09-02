<?php

namespace App\Http\Controllers;
use App\Models\BlogPost;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\BlogPostResource;


class BlogPostController extends Controller
{
    public function index(Request $request)
    {
        try{
            if($request->has('limit')){
                return BlogPostResource::collection(BlogPost::paginate($request->limit));
            }
            if($request ->has('search')){
                return BlogPostResource::collection(BlogPost::search($request -> search)->get());
            }
    
            else{
                return BlogPostResource::collection(BlogPost::all());
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
            return new BlogPostResource(BlogPost::findOrFail($id));
        }
        catch(\Exception $e){
            return $e->getMessage();
 
        }
    }

    
    public function store(Request $request)
    {
        $blogPost = new BlogPost();

        if($request->has('firstName'))
        $blogPost['firstName'] = $request->input('firstName');

        if($request->has('lastName'))
        $blogPost['lastName'] = $request->input('lastName');

        if($request->has('email'))
        $blogPost['email'] = $request->input('email');

        if($request->has('avatar'))
        $blogPost['avatar'] = $request->input('avatar');
        

        $blogPost->save();

        $success['0']['code']='0001';
        $success['0']['status']='200';
        $success['0']['title']='Submitted successfully';
        $success['0']['detail']='BlogPost placed successfully';
        return response()->json(['data', $success],'200');
    }


    public function update(Request $request , $id)
    {
        $blogPost = BlogPost::findOrFail($id);
     
        if($request->has('firstName'))
        $blogPost['firstName'] = $request->input('firstName');

        if($request->has('lastName'))
        $blogPost['lastName'] = $request->input('lastName');

        if($request->has('email'))
        $blogPost['email'] = $request->input('email');

        if($request->has('avatar'))
        $blogPost['avatar'] = $request->input('avatar');

        $blogPost->save();

        $success['0']['code']='0001';
        $success['0']['status']='200';
        $success['0']['title']='Updated successfully';
        $success['0']['detail']='BlogPost Update successfully';
        return response()->json(['data' , $success],'200');

    }


    public function delete($id)
    {
        $blogPost = BlogPost::find($id);
        $blogPost->delete();

        $success['0']['code']='0001';
        $success['0']['status']='200';
        $success['0']['title']='Deleted successfully';
        $success['0']['detail']='BlogPost Delete successfully';
        return response()->json(['data' ,  $success],'200');
    }
}
