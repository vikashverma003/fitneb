@extends('layouts.app')

@section('content')
<div style="min-height: 600px;">
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Trainer Requests
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
            <th>Status</th>
            <th>Requested On</th>
            <th style="width:100px;">Actions</th>
          </tr>
        </thead>
        <tbody>
        	<?php $i = 1; ?>
        	@foreach($trainer_requests as $trainer_request)
          <tr>
            <td>{!! $i++ !!}</td>
            <td><a href="{!! url('user_profile',$trainer_request->user->_id) !!}">{!! $trainer_request->user->first_name.' '.$trainer_request->user->last_name !!}</a></td>
            <td>{!! $trainer_request->user->email !!}</td>
            <td>
              @if($trainer_request->status=="0")
              <label class="label label-warning">Pending</label>
              @elseif($trainer_request->status=="1")
              <label class="label label-success">Accepted</label>
              @elseif($trainer_request->status=="2")
              <label class="label label-danger">Rejected</label>
              @endif
            </td>
            <td>{!! date('d-m-Y', strtotime($trainer_request->created_at)) !!}</td>
            <td>
              @if($trainer_request->status=="0")
                <form action="{!! url('respond_trainer_request') !!}" method="post">
                  @csrf
                  <input type="hidden" name="request_id" value="{!! $trainer_request->id !!}">
                	<select name="status" class="form-control" onchange="this.form.submit()">
                    <option value="">Select Option</option>
                    <option value="1">Accept</option>
                    <option value="2">Reject</option>
                  </select>
                </form>
              @endif
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