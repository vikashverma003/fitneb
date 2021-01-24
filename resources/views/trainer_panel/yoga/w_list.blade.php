@extends('trainer_panel.layouts.app')

@section('content')
<?php
use App\Trainer as Trainer;
?>
<div style="min-height: 600px;">
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Yoga Workout List
    </div>
    <div class="table-responsive" style="padding: 20px;">
      <a href="{!! url('trainer/add_yoga_workout') !!}" class="btn btn-success"><i class="fa fa-plus"></i> Add Yoga Workout</a>
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
                @foreach($yoga_workouts as $yoga_workout)
                    <tr>
                      <td>{!! $i++ !!}</td>
                      <td>{!! $yoga_workout['title'] !!}</td>
                      <td>{!! $yoga_workout['exercises'] !!}</td>
                      <td>{!! $yoga_workout['time'] !!}</td>
                      <td>{!! $yoga_workout['rating'] !!}</td>
                      <td>
                        <a href="{!! url('trainer/yoga_workout_profile',$yoga_workout['_id']) !!}"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('trainer/yoga_workout_edit',$yoga_workout['_id']) !!}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('trainer/yoga_workout_delete',$yoga_workout['_id']) !!}"><i class="fa fa-times text-danger text"></i></a>
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