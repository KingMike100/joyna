<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersRequest;
use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Session;
use App\User;
use App\Role;
use App\Photo;

class AdminUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','id');
        return view('admin.users.create',compact('roles'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsersRequest $request)
    {
         $formInput = $request->except('photo_id');

         $photo_id = $request->photo_id;
         if($photo_id){
             $photoName = $photo_id->getClientOriginalName();
             $photo_id->move('photos',$photoName);

            $photo =  Photo::create(['file'=>$photoName]);
             
             $formInput['photo_id'] = $photo->id;

         }

         $formInput['password'] = bcrypt($request->password);
         User::create($formInput);
         return redirect()->route('users.index')->with('success','User Created');
        

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
        $user = User::find($id);
        $roles = Role::pluck('name','id');

        return view('admin.users.edit',compact('user','roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UsersRequest $request, $id)
    {
        $user = User::find($id);
        $formInput = $request->except('photo_id');

        $photo_id = $request->photo_id;
        if($photo_id){
            $photoName = $photo_id->getClientOriginalName();
            $photo_id->move('photos',$photoName);

           $photo =  Photo::create(['file'=>$photoName]);
            
            $formInput['photo_id'] = $photo->id;

        }

        $formInput['password'] = bcrypt($request->password);
        $user->update($formInput);
        return redirect()->route('users.index')->with('success','User Edited Successfully');
       
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
       

        unlink(public_path().$user->photo->file);
        //User::destroy($id);
        $user->delete();
        
        return redirect()->route('users.index')->with('success','User was Deleted');

        // Session::flash('deleted user','The User was Deleted');

    }
}
