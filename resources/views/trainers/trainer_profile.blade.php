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
      Trainer Profile
    </div>
    <div class="row" style="padding: 30px;">
        <div class="col-sm-12 col-md-4">
        	<?php if((strpos($trainer['profile_image'], "http://") !== false) || (strpos($trainer['profile_image'], "https://") !== false)) { ?>
            	<img src="{!! $trainer['profile_image'] !!}" width="200" height="200">
            <?php } else { ?>
            	<img src="{!! url('public/images',$trainer['profile_image']) !!}" width="200" height="200">
            <?php } ?>
        </div>
           <div class="col-sm-12 col-md-8">
           		<div style="text-align: right;"><a href="{!! url('trainer_edit',$trainer['_id']) !!}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('trainer_delete',$trainer['_id']) !!}"><i class="fa fa-times text-danger text"></i></a></div>
                <div class="table-responsive">
                   	<table class="table">
                        <tbody>
                            <tr>
                                <td rowspan="1" valign="center">
                                    <h3>{!! ucfirst($trainer['name']) !!}</h3>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Mobile -</strong> {!! $trainer['mobile'] !!}</td>
                           	</tr>
                            <tr>
                               	<td><strong>Email -</strong> {!! $trainer['email'] !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <tbody>
                          
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
  </div>
</div>
</div>

@endsection