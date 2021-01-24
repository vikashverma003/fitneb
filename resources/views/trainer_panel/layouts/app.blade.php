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
<link rel="stylesheet" href="{!! url('public/css/morris.css') !!}" type="text/css"/>
<!-- calendar -->
<link rel="stylesheet" href="{!! url('public/css/monthly.css') !!}">
<!-- //calendar -->
<!-- //font-awesome icons -->
<script src="{!! url('public/js/jquery2.0.3.min.js') !!}"></script>
<script src="{!! url('public/js/raphael-min.js') !!}"></script>
<script src="{!! url('public/js/morris.js') !!}"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.css"/>
<style type="text/css">
    ul.top-menu>li>a:hover,ul.top-menu>li>a:focus {
        background:#08b7dd;
        text-decoration:none;
        color:#fff !important;
        padding-right:8px !important;
    }
    .nav .open>a, .nav .open>a:focus, .nav .open>a:hover {
         background:#08b7dd !important;
        color:#fff !important;
    }
    .top-nav ul.top-menu>li>a:hover,.top-nav ul.top-menu>li>a:focus {
        border:1px solid #08b7dd;
        background:#08b7dd !important;
        border-radius:100px;
        -webkit-border-radius:100px;
    }
    ul.sidebar-menu li a {
        color: #000;
    }
    ul.sidebar-menu li a.active{
        color: #000;
    }
    ul.sidebar-menu li a.active i {
        color: #000;
    }
    ul.sidebar-menu li ul.sub li a {
        color: #000;
    }
</style>
</head>
<body>
<section id="container">
<!--header start-->
<header class="header fixed-top clearfix" style="background: #CCBB00;">
<!--logo start-->
<div class="brand" style="background: #08b7dd;">
    <a href="{!! url('trainer/dashboard') !!}" class="logo">
        FITNEB
    </a>
    <div class="sidebar-toggle-box" style="background: #08b7dd;">
        <div class="fa fa-bars"></div>
    </div>
</div>
<!--logo end-->

<div class="top-nav clearfix">
    <!--search & user info start-->
    <ul class="nav pull-right top-menu">
        <li>
            <!-- <input type="text" class="form-control search" placeholder=" Search"> -->
        </li>
        <!-- user login dropdown start-->
        <li class="dropdown">
            <a data-toggle="dropdown" class="dropdown-toggle" href="#" style="background: #08b7dd;">
                <?php
                use App\Trainer as Trainer;
                $trainer = Trainer::where(['_id'=>Session::get('trainer_user_id')])->select('name')->first();
                ?>
                <img alt="" src="images/2.png">
                <span class="username"><?= $trainer->name; ?></span>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu extended logout">
                <li><a href="#"><i class=" fa fa-suitcase"></i>Profile</a></li>
                <li><a href="#"><i class="fa fa-cog"></i> Settings</a></li>
                <li><a href="{!! url('trainer/logout') !!}"><i class="fa fa-key"></i> Log Out</a></li>
            </ul>
        </li>
        <!-- user login dropdown end -->
       
    </ul>
    <!--search & user info end-->
</div>
</header>
<!--header end-->
<!--sidebar start-->
<aside>
    <div id="sidebar" class="nav-collapse" style="background: #feed02;">
        <!-- sidebar menu start-->
        <div class="leftside-navigation">
            <ul class="sidebar-menu" id="nav-accordion">
                <li>
                    <a class="active" href="{!! url('trainer/dashboard') !!}">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-book"></i>
                        <span>Running</span>
                    </a>
                    <ul class="sub">
                        <li><a href="{!! url('trainer/training_list') !!}">Training List</a></li>
                    </ul>
                </li>

                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-book"></i>
                        <span>Weight Lifting</span>
                    </a>
                    <ul class="sub">
                        <li><a href="{!! url('trainer/wl_exercise_list') !!}">Exercise List</a></li>
                        <li><a href="{!! url('trainer/wl_workout_list') !!}">Workout List</a></li>
                    </ul>
                </li>

                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-book"></i>
                        <span>Yoga</span>
                    </a>
                    <ul class="sub">
                        <li><a href="{!! url('trainer/yoga_exercise_list') !!}">Exercise List</a></li>
                        <li><a href="{!! url('trainer/yoga_workout_list') !!}">Workout List</a></li>
                    </ul>
                </li>

                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-book"></i>
                        <span>Diets</span>
                    </a>
                    <ul class="sub">
                        <li><a href="{!! url('trainer/diet_list') !!}">Diet List</a></li>
                    </ul>
                </li>
            </ul>            
        </div>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper">
        @yield('content')
    </section>
 <!-- footer -->
          <div class="footer" style="background: #CCBB00;">
            <div class="wthree-copyright">
              <p>© 2019 Fitneb. All rights reserved.</p>
            </div>
          </div>
  <!-- / footer -->
</section>
<!--main content end-->
</section>
<script src="{!! url('public/js/bootstrap.js') !!}"></script>
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.18/datatables.min.js"></script>
<script src="{!! url('public/js/jquery.dcjqaccordion.2.7.js') !!}"></script>
<script src="{!! url('public/js/scripts.js') !!}"></script>
<script src="{!! url('public/js/jquery.slimscroll.js') !!}"></script>
<script src="{!! url('public/js/jquery.nicescroll.js') !!}"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/flot-chart/excanvas.min.js"></script><![endif]-->
<script src="{!! url('public/js/jquery.scrollTo.js') !!}"></script>
<script src="{!! url('public/js/ckeditor.js') !!}"></script>
<!-- morris JavaScript -->  
<!-- calendar -->
    <script type="text/javascript" src="js/monthly.js"></script>
    <!-- //calendar -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable();
            $('.example').DataTable();
        } );
    </script>

    <script>
        ClassicEditor
            .create( document.querySelector( '#description_ckeditor' ), {
                // toolbar: [ 'heading', '|', 'bold', 'italic', 'link' ]
            } )
            .then( editor => {
                window.editor = editor;
            } )
            .catch( err => {
                console.error( err.stack );
            } );
    </script>
</body>
</html>
