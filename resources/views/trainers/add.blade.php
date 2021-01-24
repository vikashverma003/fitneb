@extends('layouts.app')

@section('content')
<div style="min-height: 600px;">
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Add Trainer
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
                <form class="cmxform form-horizontal " id="signupForm" method="post" action="{!! url('add_trainer_action') !!}" novalidate="novalidate" enctype="multipart/form-data">
                  @csrf
                    <div class="form-group ">
                      <label for="name" class="control-label col-lg-3">Name</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="name" value="{{ old('name') }}" name="name" type="text">
                        @if ($errors->has('name'))
                          <div class="alert alert-danger">
                            {!! $errors->first('name') !!}
                          </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="email" class="control-label col-lg-3">Email</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="email" value="{{ old('email') }}" name="email" type="text">
                        @if ($errors->has('email'))
                          <div class="alert alert-danger">
                            {!! $errors->first('email') !!}
                          </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="mobile" class="control-label col-lg-3">Mobile</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="mobile" value="{{ old('mobile') }}" name="mobile" type="text">
                        @if ($errors->has('mobile'))
                          <div class="alert alert-danger">
                            {!! $errors->first('mobile') !!}
                          </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="profile_image" class="control-label col-lg-3">Profile Image</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="profile_image" name="profile_image" type="file">
                        @if ($errors->has('profile_image'))
                          <div class="alert alert-danger">
                            {!! $errors->first('profile_image') !!}
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