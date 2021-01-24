@extends('trainer_panel.layouts.app')

@section('content')
<div style="min-height: 600px;">
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Edit Diet
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
                <form class="cmxform form-horizontal " id="signupForm" method="post" action="{!! url('trainer/diet_update') !!}" novalidate="novalidate" enctype="multipart/form-data">
                  @csrf
                    <div class="form-group ">
                      <input type="hidden" name="diet_id" value="{!! $diet['_id'] !!}">
                      <label for="name" class="control-label col-lg-3">Name</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="name" value="{!! $diet['name'] !!}" name="name" type="text">
                        @if ($errors->has('name'))
                          <div class="alert alert-danger">
                            {!! $errors->first('name') !!}
                          </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="description" class="control-label col-lg-3">Description</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="description" value="{!! $diet['description'] !!}" name="description" type="text">
                        @if ($errors->has('description'))
                          <div class="alert alert-danger">
                            {!! $errors->first('description') !!}
                          </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="period" class="control-label col-lg-3">Period</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="period" value="{!! $diet['period'] !!}" name="period" type="text">
                        @if ($errors->has('period'))
                          <div class="alert alert-danger">
                            {!! $errors->first('period') !!}
                          </div>
                        @endif
                      </div>
                    </div>
                    <div class="form-group ">
                      <label for="amount" class="control-label col-lg-3">Amount</label>
                      <div class="col-lg-6">
                        <input class="form-control" id="amount" value="{!! $diet['amount'] !!}" name="amount" type="text">
                        @if ($errors->has('amount'))
                          <div class="alert alert-danger">
                            {!! $errors->first('amount') !!}
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
                        <br/><br/>
                        <img src="{!! url('public/images/diet/'.$diet['image']) !!}" width="300" height="250">
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