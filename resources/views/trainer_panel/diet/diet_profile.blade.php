@extends('trainer_panel.layouts.app')

@section('content')
<style type="text/css">
	.table>tbody>tr>td {
    border-bottom: 0px solid #e9e9e9 ! important;
}
</style>
<div style="min-height: 600px;">
<div class="table-agile-info">
  <div class="panel panel-default">
    <div class="panel-heading">
      Diet Details
    </div>
    <div class="row" style="padding: 30px;">
        <div class="col-sm-12 col-md-4">
        	<img src="{!! url('public/images/diet',$diet['image']) !!}" width="200" height="200">
        </div>
           <div class="col-sm-12 col-md-8">
           		<div style="text-align: right;"><a href="{!! url('trainer/training_edit',$diet['_id']) !!}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('trainer/training_delete',$diet['_id']) !!}"><i class="fa fa-times text-danger text"></i></a></div>
                <div class="table-responsive">
                   	<table class="table">
                        <tbody>
                            <tr>
                                <td rowspan="1" valign="center">
                                    <h3>{!! ucfirst($diet['name']) !!}</h3>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Description -</strong> {!! $diet['description'] !!}</td>
                           	</tr>
                            <tr>
                               	<td><strong>Period -</strong> {!! $diet['period'] !!}</td>
                            </tr>
                            <tr>
                                <td><strong>Amount -</strong> {!! $diet['amount'] !!}</td>
                            </tr>
                            <tr>
                                <td><strong>Added on -</strong> {!! date('d-m-Y', strtotime($diet['created_at'])).' / '.date('H:i a', strtotime($diet['created_at'])) !!}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-sm-12 col-md-12">
                <a href="{!! url('trainer/add_diet_daywise',$diet['_id']) !!}" class="btn btn-success"><i class="fa fa-plus"></i> Add Diet Day wise</a>
                <br/><br/>
                <ul class="nav nav-tabs" style="padding: 20px;">
                    <li class="active"><a data-toggle="tab" href="#monday">Monday</a></li>
                    <li><a data-toggle="tab" href="#tuesday">Tuesday</a></li>
                    <li><a data-toggle="tab" href="#wednesday">Wednesday</a></li>
                    <li><a data-toggle="tab" href="#thursday">Thursday</a></li>
                    <li><a data-toggle="tab" href="#friday">Friday</a></li>
                </ul>
                <div class="tab-content">
                    <br/><br/>
                    <div id="monday" class="tab-pane fade in active">
                      <table class="table table-striped b-t b-light example">
                        <thead>
                          <tr>
                            <th style="width:20px;">#</th>
                            <th>Description</th>
                            <th style="width:100px;">Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $i = 1; ?>
                            @foreach($diet_details as $wed)
                              <?php if($wed['day'] == "1") { ?>
                                <tr>
                                  <td>{!! $i++ !!}</td>
                                  <td>{!! $wed['daily_desc'] !!}</td>
                                  <td>
                                    <a href="{!! url('trainer/diet_daywise_edit',$wed['_id']) !!}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('trainer/diet_daywise_delete',$wed['_id']) !!}"><i class="fa fa-times text-danger text"></i></a>
                                  </td>
                                </tr>
                              <?php } ?>
                            @endforeach
                        </tbody>
                      </table>
                    </div>
                    <div id="tuesday" class="tab-pane fade">
                      <table class="table table-striped b-t b-light example">
                        <thead>
                          <tr>
                            <th style="width:20px;">#</th>
                            <th>Description</th>
                            <th style="width:100px;">Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $i = 1; ?>
                            @foreach($diet_details as $wed)
                              <?php if($wed['day'] == "2") { ?>
                                <tr>
                                  <td>{!! $i++ !!}</td>
                                  <td>{!! $wed['daily_desc'] !!}</td>
                                  <td>
                                    <a href="{!! url('trainer/diet_daywise_edit',$wed['_id']) !!}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('trainer/diet_daywise_delete',$wed['_id']) !!}"><i class="fa fa-times text-danger text"></i></a>
                                  </td>
                                </tr>
                              <?php } ?>
                            @endforeach
                        </tbody>
                      </table>
                    </div>
                    <div id="wednesday" class="tab-pane fade">
                      <table class="table table-striped b-t b-light example">
                        <thead>
                          <tr>
                            <th style="width:20px;">#</th>
                            <th>Description</th>
                            <th style="width:100px;">Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $i = 1; ?>
                            @foreach($diet_details as $wed)
                              <?php if($wed['day'] == "3") { ?>
                                <tr>
                                  <td>{!! $i++ !!}</td>
                                  <td>{!! $wed['daily_desc'] !!}</td>
                                  <td>
                                    <a href="{!! url('trainer/diet_daywise_edit',$wed['_id']) !!}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('trainer/diet_daywise_delete',$wed['_id']) !!}"><i class="fa fa-times text-danger text"></i></a>
                                  </td>
                                </tr>
                              <?php } ?>
                            @endforeach
                        </tbody>
                      </table>
                    </div>
                    <div id="thursday" class="tab-pane fade">
                      <table class="table table-striped b-t b-light example">
                        <thead>
                          <tr>
                            <th style="width:20px;">#</th>
                            <th>Description</th>
                            <th style="width:100px;">Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $i = 1; ?>
                            @foreach($diet_details as $wed)
                              <?php if($wed['day'] == "4") { ?>
                                <tr>
                                  <td>{!! $i++ !!}</td>
                                  <td>{!! $wed['daily_desc'] !!}</td>
                                  <td>
                                    <a href="{!! url('trainer/diet_daywise_edit',$wed['_id']) !!}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('trainer/diet_daywise_delete',$wed['_id']) !!}"><i class="fa fa-times text-danger text"></i></a>
                                  </td>
                                </tr>
                              <?php } ?>
                            @endforeach
                        </tbody>
                      </table>
                    </div>
                    <div id="friday" class="tab-pane fade">
                      <table class="table table-striped b-t b-light example">
                        <thead>
                          <tr>
                            <th style="width:20px;">#</th>
                            <th>Description</th>
                            <th style="width:100px;">Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $i = 1; ?>
                            @foreach($diet_details as $wed)
                              <?php if($wed['day'] == "5") { ?>
                                <tr>
                                  <td>{!! $i++ !!}</td>
                                  <td>{!! $wed['daily_desc'] !!}</td>
                                  <td>
                                    <a href="{!! url('trainer/diet_daywise_edit',$wed['_id']) !!}"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;&nbsp;<a href="{!! url('trainer/diet_daywise_delete',$wed['_id']) !!}"><i class="fa fa-times text-danger text"></i></a>
                                  </td>
                                </tr>
                              <?php } ?>
                            @endforeach
                        </tbody>
                      </table>
                    </div>
                  </div>
            </div>
        </div>
  </div>
</div>
</div>

@endsection