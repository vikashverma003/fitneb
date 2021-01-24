@extends('trainer_panel.layouts.app')

@section('content')
<?php
use App\Trainer as Trainer;
?>
<div style="min-height: 600px;">
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Diet List
    </div>
    <div class="table-responsive" style="padding: 20px;">
      <a href="{!! url('trainer/add_diet') !!}" class="btn btn-success"><i class="fa fa-plus"></i> Add Diet</a>
       <br/><br/>
    	@if (session('er_status'))
        <div class="alert alert-danger">{!! session('er_status') !!}</div>
      @endif
      @if (session('su_status'))
        <div class="alert alert-success">{!! session('su_status') !!}</div>
      @endif

      <div class="tab-content">
          <table class="table table-striped b-t b-light" id="example">
            <thead>
              <tr>
                <th style="width:20px;">#</th>
                <th>Name</th>
                <th>Description</th>
                <th>Period</th>
                <th>Amount</th>
                <th>Rating</th>
                <th style="width:100px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php $i = 1; ?>
                @foreach($diets as $diet)
                    <tr>
                      <td>{!! $i++ !!}</td>
                      <td><a href="{!! url('trainer/diet_profile',$diet['_id']) !!}">{!! $diet['name'] !!}</a></td>
                      <td>{!! $diet['description'] !!}</td>
                      <td>{!! $diet['period'] !!}</td>
                      <td>{!! $diet['amount'] !!}</td>
                      <td>{!! $diet['rating'] !!}</td>
                      <td>
                        <a href="{!! url('trainer/diet_profile',$diet['_id']) !!}"><i class="fa fa-eye"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('trainer/diet_edit',$diet['_id']) !!}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('trainer/diet_delete',$diet['_id']) !!}"><i class="fa fa-times text-danger text"></i></a>
                      </td>
                    </tr>
                @endforeach
            </tbody>
          </table>
      </div>
    </div>
  </div>
</div>
</div>

@endsection