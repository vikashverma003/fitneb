@extends('trainer_panel.layouts.app')

@section('content')
<div style="min-height: 600px;">
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Edit Weightlifting Excercise daywise
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
                <form class="cmxform form-horizontal " id="signupForm" method="post" action="{!! url('trainer/wl_ex_daywise_update') !!}" novalidate="novalidate" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="wl_ex_daywise_id" value="{!! $wl_ex_daywise_detail['_id'] !!}">
                    <div class="form-group ">
                      <label for="day" class="control-label col-lg-3">Day</label>
                      <div class="col-lg-6">
                        <select class="form-control" id="day" name="day">
                          <option value="">Select Day</option>
                          <option value="1" <?php if($wl_ex_daywise_detail['day']=="1") echo "selected"; ?>>Monday</option>
                          <option value="2" <?php if($wl_ex_daywise_detail['day']=="2") echo "selected"; ?>>Tuesday</option>
                          <option value="3" <?php if($wl_ex_daywise_detail['day']=="3") echo "selected"; ?>>Wednesday</option>
                          <option value="4" <?php if($wl_ex_daywise_detail['day']=="4") echo "selected"; ?>>Thursday</option>
                          <option value="5" <?php if($wl_ex_daywise_detail['day']=="5") echo "selected"; ?>>Friday</option>
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
                        <input class="form-control" id="title" name="title" type="text" value="{!! $wl_ex_daywise_detail['title'] !!}">
                        @if ($errors->has('title'))
                          <div class="alert alert-danger">
                            {!! $errors->first('title') !!}
                          </div>
                        @endif
                      </div>
                    </div>

                    <div class="form-group ">
                      <label for="daily_desc" class="control-label col-lg-3">Subtitle</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="daily_desc" name="daily_desc" type="text" value="{!! $wl_ex_daywise_detail['daily_desc'] !!}">
                        @if ($errors->has('daily_desc'))
                          <div class="alert alert-danger">
                            {!! $errors->first('daily_desc') !!}
                          </div>
                        @endif
                      </div>
                    </div>

                    <div class="form-group ">
                      <label for="reps" class="control-label col-lg-3">Reps</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="reps" name="reps" type="text" value="{!! $wl_ex_daywise_detail['reps'] !!}">
                        @if ($errors->has('reps'))
                          <div class="alert alert-danger">
                            {!! $errors->first('reps') !!}
                          </div>
                        @endif
                      </div>
                    </div>

                    <div class="form-group ">
                      <label for="rest_time" class="control-label col-lg-3">Rest Time</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="rest_time" name="rest_time" type="text" value="{!! $wl_ex_daywise_detail['rest_time'] !!}">
                        @if ($errors->has('rest_time'))
                          <div class="alert alert-danger">
                            {!! $errors->first('rest_time') !!}
                          </div>
                        @endif
                      </div>
                    </div>

                    <div class="form-group ">
                      <label for="image" class="control-label col-lg-3">Image</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="image" name="image" type="file">
                        <br/>
                        <img src="{!! url('public/images/image',$wl_ex_daywise_detail['image']) !!}" width="200" height="200">
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