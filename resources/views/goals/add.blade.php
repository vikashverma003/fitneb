@extends('layouts.app')

@section('content')
<div style="min-height: 600px;">
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Add Goal
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
                <form class="cmxform form-horizontal " id="signupForm" method="post" action="{!! url('add_goals_action') !!}" novalidate="novalidate">
                  @csrf
                    <div class="form-group ">
                      <label for="goal" class="control-label col-lg-3">Goal</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="goal" name="goal" type="text">
                        @if ($errors->has('goal'))
                          <div class="alert alert-danger">
                            {!! $errors->first('goal') !!}
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