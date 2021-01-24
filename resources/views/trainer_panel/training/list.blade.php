@extends('trainer_panel.layouts.app')

@section('content')
<?php
use App\Trainer as Trainer;
?>
<div style="min-height: 600px;">
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Training List
    </div>
    <div class="table-responsive" style="padding: 20px;">
      <a href="{!! url('trainer/add_training') !!}" class="btn btn-success"><i class="fa fa-plus"></i> Add Training</a>
       <br/><br/>
    	@if (session('er_status'))
        <div class="alert alert-danger">{!! session('er_status') !!}</div>
      @endif
      @if (session('su_status'))
        <div class="alert alert-success">{!! session('su_status') !!}</div>
      @endif

      <div class="tab-content">
          <table class="table table-striped b-t b-light" id="example">
            <thead>
              <tr>
                <th style="width:20px;">#</th>
                <th>Title</th>
                <th>Exercises</th>
                <th>Time</th>
                <th>Ratings</th>
                <th style="width:100px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; ?>
                @foreach($trainings as $training)
                    <tr>
                      <td>{!! $i++ !!}</td>
                      <td><a href="{!! url('trainer/training_profile',$training['_id']) !!}">{!! $training['title'] !!}</a></td>
                      <td>{!! $training['exercises'] !!}</td>
                      <td>{!! $training['time'] !!}</td>
                      <td>{!! $training['rating'] !!}</td>
                      <td>
                        <a href="{!! url('trainer/training_profile',$training['_id']) !!}"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('trainer/training_edit',$training['_id']) !!}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('trainer/training_delete',$training['_id']) !!}"><i class="fa fa-times text-danger text"></i></a>
                      </td>
                    </tr>
                @endforeach
            </tbody>
          </table>
      </div>
    </div>
  </div>
</div>
</div>

@endsection