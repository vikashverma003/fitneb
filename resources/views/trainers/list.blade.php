@extends('layouts.app')

@section('content')
<div style="min-height: 600px;">
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Trainers List
    </div>
    <div class="table-responsive" style="padding: 20px;">
      <a href="{!! url('add_trainer') !!}" class="btn btn-success"><i class="fa fa-plus"></i> Add Trainer</a>
       <br/><br/>
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
            <th>Mobile</th>
            <!-- <th>Registered Date</th> -->
            <th style="width:100px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        	<?php $i = 1; ?>
        	@foreach($trainers as $trainer)
          <tr>
            <td>{!! $i++ !!}</td>
            <td>{!! $trainer['name'] !!}</td>
            <td>{!! $trainer['email'] !!}</td>
            <td>{!! $trainer['mobile'] !!}</td>
            <!-- <td>{!! date('d-m-Y', strtotime($trainer['created_at'])) !!}</td> -->
            <td>
            	<a href="{!! url('trainer_profile',$trainer['_id']) !!}"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('trainer_edit',$trainer['_id']) !!}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('trainer_delete',$trainer['_id']) !!}"><i class="fa fa-times text-danger text"></i></a>
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