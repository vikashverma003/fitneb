<!DOCTYPE html>
<head>
<title>Fitneb</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="Visitors Responsive web template, Bootstrap Web Templates, Flat Web Templates, Android Compatible web template, 
Smartphone Compatible web template, free webdesigns for Nokia, Samsung, LG, SonyEricsson, Motorola web design" />
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<!-- bootstrap-css -->
<link rel="stylesheet" href="{!! url('public/css/bootstrap.min.css') !!}" >
<!-- //bootstrap-css -->
<!-- Custom CSS -->
<link href="{!! url('public/css/style.css') !!}" rel='stylesheet' type='text/css' />
<link href="{!! url('public/css/style-responsive.css') !!}" rel="stylesheet"/>
<!-- font CSS -->
<link href='//fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,300italic,400italic,500,500italic,700,700italic,900,900italic' rel='stylesheet' type='text/css'>
<!-- font-awesome icons -->
<link rel="stylesheet" href="{!! url('public/css/font.css') !!}" type="text/css"/>
<link href="{!! url('public/css/font-awesome.css') !!}" rel="stylesheet"> 
<!-- //font-awesome icons -->
<script src="{!! url('public/js/jquery2.0.3.min.js') !!}"></script>
<style type="text/css">
	.w3layouts-main input[type="submit"]{
		background:#08b7dd;
	}
	.w3layouts-main input[type="submit"]:hover {
		background:#08b7dd;
		transition:0.5s all;
		-webkit-transition:0.5s all;
		-o-transition:0.5s all;
		-moz-transition:0.5s all;
		-ms-transition:0.5s all;
	}
	input.ggg {
	    width: 100%;
	    padding: 15px 0px 15px 15px;
	    border: 1px solid #fff;
	    outline: none;
	    font-size: 14px;
	    color: #000;
	    margin: 14px 0px;
	    background: #fff;
	}
</style>
</head>
<body>
<div class="log-w3">
<div class="w3layouts-main" style="background: #feed02;">
	<h2 style="color:#000;">FITNEB</h2>
		@if (session('er_status'))
            <div class="alert alert-danger">{!! session('er_status') !!}</div>
        @endif
		<form action="{!! url('adminlogin') !!}" method="post">
			@csrf
			<input type="email" class="ggg" name="email" placeholder="E-MAIL" required="">
			<input type="password" class="ggg" name="password" placeholder="PASSWORD" required="">
			<!-- <span><input type="checkbox" />Remember Me</span> -->
			<!-- <h6><a href="#">Forgot Password?</a></h6> -->
				<div class="clearfix"></div>
				<input type="submit" value="Sign In" name="login">
		</form>
		<!-- <p>Don't Have an Account ?<a href="registration.html">Create an account</a></p> -->
</div>
</div>
<script src="{!! url('public/js/bootstrap.js') !!}"></script>
<script src="{!! url('public/js/jquery.dcjqaccordion.2.7.js') !!}"></script>
<script src="{!! url('public/js/scripts.js') !!}"></script>
<script src="{!! url('public/js/jquery.slimscroll.js') !!}"></script>
<script src="{!! url('public/js/jquery.nicescroll.js') !!}"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/flot-chart/excanvas.min.js"></script><![endif]-->
<script src="{!! url('public/js/jquery.scrollTo.js') !!}"></script>
</body>
</html>
