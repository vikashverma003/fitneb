@extends('layouts.app')

@section('content')
<div style="min-height: 600px;">
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Edit User
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
                <form class="cmxform form-horizontal " id="signupForm" method="post" action="{!! url('user_update') !!}" novalidate="novalidate" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="user_id" value="{!! $user['_id'] !!}">
                    <div class="form-group ">
                      <label for="firstname" class="control-label col-lg-3">First Name</label>
                      <div class="col-lg-6">
                        <input class=" form-control" id="firstname" name="first_name" value="{!! $user['first_name'] !!}" type="text">
                        @if ($errors->has('first_name'))
                          <div class="alert alert-danger">
                            {!! $errors->first('first_name') !!}
                          </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="lastname" class="control-label col-lg-3">Last Name</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="lastname" name="last_name" value="{!! $user['last_name'] !!}" type="text">
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="email" class="control-label col-lg-3">Email</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="email" name="email" type="text" value="{!! $user['email'] !!}" readonly="">
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="age" class="control-label col-lg-3">Age</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="age" name="age" type="number" value="{!! $user['age'] !!}">
                        @if ($errors->has('age'))
                          <div class="alert alert-danger">
                            {!! $errors->first('age') !!}
                          </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="bio" class="control-label col-lg-3">Bio</label>
                        <div class="col-lg-6">
                        <input class="form-control " id="bio" name="bio" type="text" value="{!! $user['bio'] !!}">
                        @if ($errors->has('bio'))
                          <div class="alert alert-danger">
                            {!! $errors->first('bio') !!}
                          </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="body_mass" class="control-label col-lg-3">Body Mass</label>
                      <div class="col-lg-6">
                        <input class="form-control " id="body_mass" name="body_mass" type="text" value="{!! $user['body_mass'] !!}">
                        @if ($errors->has('body_mass'))
                          <div class="alert alert-danger">
                            {!! $errors->first('body_mass') !!}
                          </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="goal1" class="control-label col-lg-3">Goals</label>
                      <div class="col-lg-6">
                        <input class="form-control " id="goal1" name="goal1" type="text" value="{!! $user['goal1'] !!}">
                        <input class="form-control " id="goal2" name="goal2" type="text" value="{!! $user['goal2'] !!}">
                        <input class="form-control " id="goal3" name="goal3" type="text" value="{!! $user['goal3'] !!}">
                        @if ($errors->has('goal1'))
                          <div class="alert alert-danger">
                            {!! $errors->first('goal1') !!}
                          </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="height" class="control-label col-lg-3">Height</label>
                      <div class="col-lg-6">
                        <input class="form-control " id="height" name="height" type="text" value="{!! $user['height'] !!}">
                        @if ($errors->has('height'))
                          <div class="alert alert-danger">
                            {!! $errors->first('height') !!}
                          </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="weight" class="control-label col-lg-3">Weight</label>
                      <div class="col-lg-6">
                        <input class="form-control " id="weight" name="weight" type="text" value="{!! $user['weight'] !!}">
                        @if ($errors->has('weight'))
                          <div class="alert alert-danger">
                            {!! $errors->first('weight') !!}
                          </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="profile_image" class="control-label col-lg-3">Profile Image</label>
                      <div class="col-lg-6">
                        <input class="form-control " id="profile_image" name="profile_image" type="file">
                        <br/>
                        <?php if((strpos($user['profile_image'], "http://") !== false) || (strpos($user['profile_image'], "https://") !== false)) { ?>
                          <img src="{!! $user['profile_image'] !!}" width="200" height="200">
                        <?php } else { ?>
                          <img src="{!! url('public/images/',$user['profile_image']) !!}" width="200" height="200">
                        <?php } ?>
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