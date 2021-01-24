@extends('trainer_panel.layouts.app')

@section('content')
<div style="min-height: 600px;">
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Edit Training Workout
    </div>
      <div class="row">
        <div class="col-lg-12">
          <section class="panel">
            <div class="panel-body">
              <div class="form">
                @if (session('er_status'))
                  <div class="alert alert-danger">{!! session('er_status') !!}</div>
                @endif
                @if (session('su_status'))
                  <div class="alert alert-success">{!! session('su_status') !!}</div>
                @endif
                <form class="cmxform form-horizontal " id="signupForm" method="post" action="{!! url('trainer/training_worouts_update') !!}" novalidate="novalidate" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="trainingWorkout_id" value="{!! $training_workout['_id'] !!}">
                    <div class="form-group ">
                      <label for="day" class="control-label col-lg-3">Title</label>
                      <div class="col-lg-6">
                        <select class="form-control" id="day" name="day">
                          <option value="">Select Day</option>
                          <option value="1" <?php if($training_workout['day']=="1") echo "selected"; ?>>Monday</option>
                          <option value="2" <?php if($training_workout['day']=="2") echo "selected"; ?>>Tuesday</option>
                          <option value="3" <?php if($training_workout['day']=="3") echo "selected"; ?>>Wednesday</option>
                          <option value="4" <?php if($training_workout['day']=="4") echo "selected"; ?>>Thursday</option>
                          <option value="5" <?php if($training_workout['day']=="5") echo "selected"; ?>>Friday</option>
                        </select>
                        @if ($errors->has('day'))
                          <div class="alert alert-danger">
                            {!! $errors->first('day') !!}
                          </div>
                        @endif
                      </div>
                    </div>

                    <div class="form-group ">
                      <label for="title" class="control-label col-lg-3">Title</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="title" name="title" type="text" value="{!! $training_workout['title'] !!}">
                        @if ($errors->has('title'))
                          <div class="alert alert-danger">
                            {!! $errors->first('title') !!}
                          </div>
                        @endif
                      </div>
                    </div>

                    <div class="form-group ">
                      <label for="subtitle" class="control-label col-lg-3">Subtitle</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="subtitle" name="subtitle" type="text" value="{!! $training_workout['subtitle'] !!}">
                        @if ($errors->has('subtitle'))
                          <div class="alert alert-danger">
                            {!! $errors->first('subtitle') !!}
                          </div>
                        @endif
                      </div>
                    </div>

                    <div class="form-group ">
                      <label for="miles" class="control-label col-lg-3">Miles</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="miles" name="miles" type="text" value="{!! $training_workout['miles'] !!}">
                        @if ($errors->has('miles'))
                          <div class="alert alert-danger">
                            {!! $errors->first('miles') !!}
                          </div>
                        @endif
                      </div>
                    </div>

                    <div class="form-group ">
                      <label for="time" class="control-label col-lg-3">Time</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="time" name="time" type="text" value="{!! $training_workout['time'] !!}">
                        @if ($errors->has('time'))
                          <div class="alert alert-danger">
                            {!! $errors->first('time') !!}
                          </div>
                        @endif
                      </div>
                    </div>

                    <div class="form-group ">
                      <label for="image" class="control-label col-lg-3">Image</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="image" name="image" type="file">
                        <br/>
                        <img src="{!! url('public/images/image',$training_workout['image']) !!}" width="200" height="200">
                        @if ($errors->has('image'))
                          <div class="alert alert-danger">
                            {!! $errors->first('image') !!}
                          </div>
                        @endif
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-lg-offset-3 col-lg-6">
                        <button class="btn btn-primary" type="submit">Update</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </section>
          </div>
        </div>
  </div>
</div>
</div>

@endsection