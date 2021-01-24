@extends('trainer_panel.layouts.app')

@section('content')
<div style="min-height: 600px;">
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Add Yoga Excercise daywise
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
                <form class="cmxform form-horizontal " id="signupForm" method="post" action="{!! url('trainer/add_yoga_ex_daywise_action') !!}" novalidate="novalidate" enctype="multipart/form-data">
                  @csrf
                    <div class="form-group ">
                      <input type="hidden" name="yoga_excercise_id" id="yoga_excercise_id" value="{!! $id !!}">
                      <label for="day" class="control-label col-lg-3">Day</label>
                      <div class="col-lg-6">
                        <select class="form-control" id="day" name="day">
                          <option value="">Select Day</option>
                          <option value="1" <?php if(old('day') == "1") echo "selected"; ?>>Monday</option>
                          <option value="2" <?php if(old('day') == "2") echo "selected"; ?>>Tuesday</option>
                          <option value="3" <?php if(old('day') == "3") echo "selected"; ?>>Wednesday</option>
                          <option value="4" <?php if(old('day') == "4") echo "selected"; ?>>Thursday</option>
                          <option value="5" <?php if(old('day') == "5") echo "selected"; ?>>Friday</option>
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
                        <input class="form-control" id="title" value="{{ old('title') }}" name="title" type="text">
                        @if ($errors->has('title'))
                          <div class="alert alert-danger">
                            {!! $errors->first('title') !!}
                          </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="daily_desc" class="control-label col-lg-3">Description</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="daily_desc" value="{{ old('daily_desc') }}" name="daily_desc" type="text">
                        @if ($errors->has('daily_desc'))
                          <div class="alert alert-danger">
                            {!! $errors->first('daily_desc') !!}
                          </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="sets" class="control-label col-lg-3">Sets</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="sets" value="{{ old('sets') }}" name="sets" type="text">
                        @if ($errors->has('sets'))
                          <div class="alert alert-danger">
                            {!! $errors->first('sets') !!}
                          </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="hold_time" class="control-label col-lg-3">Hold Time</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="hold_time" value="{{ old('hold_time') }}" name="hold_time" type="text">
                        @if ($errors->has('hold_time'))
                          <div class="alert alert-danger">
                            {!! $errors->first('hold_time') !!}
                          </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="image" class="control-label col-lg-3">Image</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="image" name="image" type="file">
                        @if ($errors->has('image'))
                          <div class="alert alert-danger">
                            {!! $errors->first('image') !!}
                          </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group">
                      <div class="col-lg-offset-3 col-lg-6">
                        <button class="btn btn-primary" type="submit">Submit</button>
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