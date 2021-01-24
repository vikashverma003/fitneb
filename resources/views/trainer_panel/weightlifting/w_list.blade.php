@extends('trainer_panel.layouts.app')

@section('content')
<?php
use App\Trainer as Trainer;
?>
<div style="min-height: 600px;">
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Weightlifting Workout List
    </div>
    <div class="table-responsive" style="padding: 20px;">
      <a href="{!! url('trainer/add_weightlift_workout') !!}" class="btn btn-success"><i class="fa fa-plus"></i> Add Workout</a>
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
                @foreach($wl_workouts as $wl_workout)
                    <tr>
                      <td>{!! $i++ !!}</td>
                      <td>{!! $wl_workout['title'] !!}</td>
                      <td>{!! $wl_workout['exercises'] !!}</td>
                      <td>{!! $wl_workout['time'] !!}</td>
                      <td>{!! $wl_workout['rating'] !!}</td>
                      <td>
                        <a href="{!! url('trainer/wl_workout_profile',$wl_workout['_id']) !!}"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('trainer/wl_workout_edit',$wl_workout['_id']) !!}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('trainer/wl_workout_delete',$wl_workout['_id']) !!}"><i class="fa fa-times text-danger text"></i></a>
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