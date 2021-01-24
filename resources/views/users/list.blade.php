@extends('layouts.app')

@section('content')
<div style="min-height: 600px;">
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Users List
    </div>
    <div class="table-responsive" style="padding: 20px;">
    	@if (session('er_status'))
                <div class="alert alert-danger">{!! session('er_status') !!}</div>
            @endif
            @if (session('su_status'))
                <div class="alert alert-success">{!! session('su_status') !!}</div>
            @endif
      <table class="table table-striped b-t b-light" id="example">
        <thead>
          <tr>
            <th style="width:20px;">#</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Location</th>
            <th>Registered Date</th>
            <th style="width:100px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        	<?php $i = 1; ?>
        	@foreach($users as $user)
          <tr>
            <td>{!! $i++ !!}</td>
            <td>{!! $user['first_name']." ".$user['last_name'] !!}</td>
            <td>{!! $user['email'] !!}</td>
            <td>{!! $user['location'] !!}</td>
            <td>{!! date('d-m-Y', strtotime($user['created_at'])) !!}</td>
            <td>
            	<a href="{!! url('user_profile',$user['_id']) !!}"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('user_edit',$user['_id']) !!}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('user_delete',$user['_id']) !!}"><i class="fa fa-times text-danger text"></i></a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
</div>

@endsection