@extends('layouts.app')

@section('content')
<?php
use App\Trainer as Trainer;
?>
<div style="min-height: 600px;">
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Weight Lifting
    </div>
    <div class="table-responsive" style="padding: 20px;">
    	@if (session('er_status'))
        <div class="alert alert-danger">{!! session('er_status') !!}</div>
      @endif
      @if (session('su_status'))
        <div class="alert alert-success">{!! session('su_status') !!}</div>
      @endif
      <ul class="nav nav-tabs" style="padding: 20px;">
        <li class="active"><a data-toggle="tab" href="#exercises">Exercises</a></li>
        <li><a data-toggle="tab" href="#workouts">Workouts</a></li>
      </ul>

      <div class="tab-content">
        <div id="exercises" class="tab-pane fade in active">
          <table class="table table-striped b-t b-light" id="example">
            <thead>
              <tr>
                <th style="width:20px;">#</th>
                <th>Added By</th>
                <th>Title</th>
                <th>Exercises</th>
                <th>Time</th>
                <th style="width:100px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; ?>
                @foreach($weightlifting as $wl)
                  <?php if($wl['type'] == "exercise") { ?>
                    <tr>
                      <td>{!! $i++ !!}</td>
                      <td>
                        <?php 
                            $trainerdetails = Trainer::where(['_id'=>$wl['user_id']])->select('name')->first();
                            echo $trainerdetails['name'];
                        ?>
                      </td>
                      <td>{!! $wl['title'] !!}</td>
                      <td>{!! $wl['exercises'] !!}</td>
                      <td>{!! $wl['time'] !!}</td>
                      <td>
                        <a href="{!! url('weightlifting_excercise_profile',$wl['_id']) !!}"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('weightlifting_excercise_edit',$wl['_id']) !!}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('weightlifting_excercise_delete',$wl['_id']) !!}"><i class="fa fa-times text-danger text"></i></a>
                      </td>
                    </tr>
                  <?php } ?>
                @endforeach
            </tbody>
          </table>
        </div>
        <div id="workouts" class="tab-pane fade">
          <table class="table table-striped b-t b-light" id="example1">
            <thead>
              <tr>
                <th style="width:20px;">#</th>
                <th>Added By</th>
                <th>Title</th>
                <th>Exercises</th>
                <th>Time</th>
                <th style="width:100px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; ?>
                @foreach($weightlifting as $wl)
                  <?php if($wl['type'] == "workout") { ?>
                    <tr>
                      <td>{!! $i++ !!}</td>
                      <td>
                        <?php 
                            $trainerdetails = Trainer::where(['_id'=>$wl['user_id']])->select('name')->first();
                            echo $trainerdetails['name'];
                        ?>
                      </td>
                      <td>{!! $wl['title'] !!}</td>
                      <td>{!! $wl['exercises'] !!}</td>
                      <td>{!! $wl['time'] !!}</td>
                      <td>
                        <a href="{!! url('weightlifting_workout_profile',$wl['_id']) !!}"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('weightlifting_workout_edit',$wl['_id']) !!}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('weightlifting_workout_delete',$wl['_id']) !!}"><i class="fa fa-times text-danger text"></i></a>
                      </td>
                    </tr>
                  <?php } ?>
                @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
</div>

@endsection