@extends('layouts.master')

@section('title','User Profile')

@section('content')

<div class="container-fluid">
    <h3 class="mb-3 text-center">{{ $user->name }}</h3>
    <div class="row">
        <div class="col-md-4 text-center animated fadeIn">
            <div class="user-avatar">
                @if ($user->gender == 'M')
                    <img src="{{ asset('storage/images/img_avatar_m.png') }}" width="250"/>
                @else
                    <img src="{{ asset('storage/images/img_avatar_f.png') }}" width="250"/>
                @endif
            </div>
            <div class="edit-link">
                <a href="{{ route('user.edit') }}" class="btn btn-primary my-4" >Edit Profile</a>
            </div>
        </div>
        <div class="col-md-8 animated fadeIn">
            <div>
                <h5 class="text-primary ml-2">Basic Details</h5>
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td>Email: </td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td>Gender: </td>
                            <td>
                                @if ($user->gender == 'M')
                                    Male
                                @else
                                    Female
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Birth Year: </td>
                            <td>{{ $user->birth }}</td>
                        </tr>
                        <tr>
                            <td>Major: </td>
                            <td>{{ $user->major }}</td>
                        </tr>
                        <tr>
                            <td>University: </td>
                            <td>{{ $user->university }}</td>
                        </tr>
                        <tr>
                            <td>Country: </td>
                            <td>{{ $user->country }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="row my-4">
        <div class="col">
            <hr/>
            <div class="text-center">
                @if(count($user->completed_modules) != 0)
                    <h4 class="mb-3 text-primary ml-3">Completed Modules</h4>
                    @foreach ($user->completed_modules as $module)
                        <span class="profile-completed-modules">
                            {{$module->name}} : @if ($module->grade == null) <em>Not Graded</em> @else {{$module->grade}}@endif
                        </span>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

@stop