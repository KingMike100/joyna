<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PostsRequest;
use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Photo;
use App\Post;
use App\Category;

class AdminPostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return view('admin.posts.index', compact('posts'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::pluck('name','id');
        return view('admin.posts.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostsRequest $request)
    {
    
        $formInput = $request->except('photo_id');
        $user = Auth::user();

        $photo_id = $request->photo_id;
        if($photo_id){
            $photoName = $photo_id->getClientOriginalName();
            $photo_id->move('photos',$photoName);

           $photo =  Photo::create(['file'=>$photoName]);
            
            $formInput['photo_id'] = $photo->id;

        }

        $user->posts()->create($formInput);

        return redirect()->route('posts.index')->with('success','Post Created');
       

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::find($id);

        $categories = Category::pluck('name','id');
       
        return view('admin.posts.edit',compact('categories','post'));


       
    
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostsRequest $request, $id)
    {
//$post = Post::find($id);
        $formInput = $request->all();
       // $user = Auth::user();
        $post = Post::find($id);

        $photo_id = $request->photo_id;
        if($photo_id){
            $photoName = $photo_id->getClientOriginalName();
            $photo_id->move('photos',$photoName);

           $photo =  Photo::create(['file'=>$photoName]);
            
            $formInput['photo_id'] = $photo->id;

        }

//        $user->posts()->whereId($id)->first()->update($formInput);
        // Auth::user()->posts()->whereId($id)->first()->update($input);
        $post->update($formInput);
        return redirect()->route('posts.index')->with('success','Post Updated');
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);

        unlink(public_path() . $post->photo->file);

        $post->delete();

        return redirect()->route('posts.index')->with('success','Post was Deleted');
    }
    
}
