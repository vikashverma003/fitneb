@extends('layouts.app')

@section('content')
<style type="text/css">
	.table>tbody>tr>td {
    border-bottom: 0px solid #e9e9e9 ! important;
}
</style>
<div style="min-height: 600px;">
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      User Profile
    </div>
    <div class="row" style="padding: 30px;">
        <div class="col-sm-12 col-md-4">
        	<?php if((strpos($user['profile_image'], "http://") !== false) || (strpos($user['profile_image'], "https://") !== false)) { ?>
            	<img src="{!! $user['profile_image'] !!}" width="200" height="200">
            <?php } else { ?>
            	<img src="{!! url('public/images/',$user['profile_image']) !!}" width="200" height="200">
            <?php } ?>
        </div>
           <div class="col-sm-12 col-md-8">
           		<div style="text-align: right;"><a href="{!! url('user_edit',$user['_id']) !!}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('user_delete',$user['_id']) !!}"><i class="fa fa-times text-danger text"></i></a></div>
                <div class="table-responsive">
                   	<table class="table">
                        <tbody>
                            <tr>
                                <td rowspan="1" valign="center">
                                    <h3>{!! ucfirst($user['first_name']." ".$user['last_name']) !!}</h3>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Location -</strong> {!! $user['location'] !!}</td>
                           	</tr>
                            <tr>
                               	<td><strong>Email -</strong> {!! $user['email'] !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                            <tr>
			                    <td><strong>Age</strong></td>
			                    <td>{!! $user['age'] !!}</td>
			                </tr>
			                <tr>
			                    <td><strong>Bio</strong></td>
			                    <td>{!! $user['bio'] !!}</td>
			                </tr>
			                <tr>
			                    <td><strong>Body Mass</strong></td>
			                    <td>{!! $user['body_mass'] !!}</td>
			                </tr>
			                <tr>
			                    <td><strong>Goals</strong></td>
			                    <td>{!! $user['goal1'] !!}<br/>{!! $user['goal2'] !!}<br/>{!! $user['goal3'] !!}</td>
			                </tr>
			                <tr>
			                    <td><strong>Height</strong></td>
			                    <td>{!! $user['height'] !!}</td>
			                </tr>
			                <tr>
			                    <td><strong>Weight</strong></td>
			                    <td>{!! $user['weight'] !!}</td>
			                </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
  </div>
</div>
</div>

@endsection