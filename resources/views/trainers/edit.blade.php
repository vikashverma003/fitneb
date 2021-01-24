@extends('layouts.app')

@section('content')
<div style="min-height: 600px;">
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Edit Trainer
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
                <form class="cmxform form-horizontal " id="signupForm" method="post" action="{!! url('trainer_update') !!}" novalidate="novalidate" enctype="multipart/form-data">
                  @csrf
                  <input type="hidden" name="trainer_id" value="{!! $trainer['_id'] !!}">
                    <div class="form-group ">
                      <label for="name" class="control-label col-lg-3">Name</label>
                      <div class="col-lg-6">
                        <input class=" form-control" id="name" name="name" value="{!! $trainer['name'] !!}" type="text">
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
                        <input class="form-control" id="email" name="email" type="text" value="{!! $trainer['email'] !!}" readonly="">
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="mobile" class="control-label col-lg-3">Mobile</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="mobile" name="mobile" type="text" value="{!! $trainer['mobile'] !!}">
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
                        <input class="form-control " id="profile_image" name="profile_image" type="file">
                        <br/>
                        <?php if((strpos($trainer['profile_image'], "http://") !== false) || (strpos($trainer['profile_image'], "https://") !== false)) { ?>
                          <img src="{!! $trainer['profile_image'] !!}" width="200" height="200">
                        <?php } else { ?>
                          <img src="{!! url('public/images/',$trainer['profile_image']) !!}" width="200" height="200">
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