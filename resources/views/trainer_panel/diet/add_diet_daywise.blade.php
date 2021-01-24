@extends('trainer_panel.layouts.app')

@section('content')
<style>
.ck-editor__editable_inline {
    min-height: 500px;
}
</style>
<div style="min-height: 600px;">
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Add Diet daywise
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
                <form class="cmxform form-horizontal " id="signupForm" method="post" action="{!! url('trainer/add_diet_daywise_action') !!}" novalidate="novalidate" enctype="multipart/form-data">
                  @csrf
                    <div class="form-group ">
                      <input type="hidden" name="diet_id" id="diet_id" value="{!! $id !!}">
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
                      <label for="description" class="control-label col-lg-3">Description</label>
                      <div class="col-lg-6">
                        <textarea class="form-control" id="description_ckeditor" name="description" rows=80 cols="60">{{ old('description') }}</textarea>
                        @if ($errors->has('description'))
                          <div class="alert alert-danger">
                            {!! $errors->first('description') !!}
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