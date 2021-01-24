@extends('layouts.app')

@section('content')
<style type="text/css">
  .btn-success {
    color: #fff;
    background-color: #08b7dd;
    border-color: #08b7dd;
  }
  .btn-success:hover{
    color: #fff;
      background-color: #08b7dd;
      border-color: #08b7dd;
  }
</style>
<div style="min-height: 600px;">
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Goals List
    </div>
    <div class="table-responsive" style="padding: 20px;">
      <a href="{!! url('add_goals') !!}" class="btn btn-success"><i class="fa fa-plus"></i> Add Goal</a>
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
            <th>Goal</th>
            <th style="width:100px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        	<?php $i = 1; ?>
        	@foreach($goals as $goal)
          <tr>
            <td>{!! $i++ !!}</td>
            <td>{!! $goal['goal'] !!}</td>
            <td>
            	<a href="{!! url('goals_edit',$goal['_id']) !!}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('goals_delete',$goal['_id']) !!}"><i class="fa fa-times text-danger text"></i></a>
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