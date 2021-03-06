@extends('layouts.admin')
@section('content')
<div class="well">  
<h1> Users </h1>
</div>

<table class="table table-bordered">
    <tr class="info">
        <th>id</th>
        <th>Name</th>
        <th>Photo</th>
        <th>email</th>
        <th>Role</th>
        <th>Active</th>
        <th>created_at</th>
        <th>updated_at</th>
        


    </tr>
     @foreach($users as $user)
        <tr class="active">
            <td>{{$user->id}}</td>
            <td><a href="{{route('users.edit', $user->id)}}">{{$user->name}}</td>

            @if ($user->photo)
            <td> <img  width = "100" height = "100" src="{{$user->photo['file']}}" /></td>   {{--add /images before the image path works incase theres no mutator --}}
            @else
            <td>          
         <img src="https://via.placeholder.com/100" alt="">
            </td>

            @endif


            
            {{-- <td> <img width = "200" src="/photos/{{$user->photo['file']}}"/></td> --}}
            <td>{{$user->email}}</td>
            <td>{{$user->role['name']}}</td> {{-- Reurns an array and should be accesed with [] instead of -> --}}
            <td>{{$user->is_active == 1 ? 'Active' : 'Not Active'}}</td>
            <td>{{$user->created_at->diffForHumans()}}</td>
            <td>{{$user->updated_at->diffForHumans()}}</td>
        </tr>

    @endforeach
</table>




@endsection