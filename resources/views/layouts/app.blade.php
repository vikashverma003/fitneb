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
    <a href="{!! url('dashboard') !!}" class="logo">
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
                use App\Admin as Admin;
                $admin = Admin::where(['_id'=>Session::get('admin_user_id')])->select('full_name')->first();
                ?>
                <img alt="" src="images/2.png">
                <span class="username"><?= $admin->full_name; ?></span>
                <b class="caret"></b>
            </a>
            <ul class="dropdown-menu extended logout">
                <li><a href="#"><i class=" fa fa-suitcase"></i>Profile</a></li>
                <li><a href="#"><i class="fa fa-cog"></i> Settings</a></li>
                <li><a href="{!! url('logout') !!}"><i class="fa fa-key"></i> Log Out</a></li>
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
                    <a class="active" href="{!! url('dashboard') !!}">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-book"></i>
                        <span>Users Management</span>
                    </a>
                    <ul class="sub">
                        <li><a href="{!! url('users_list') !!}">Users List</a></li>
                    </ul>
                </li>

                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-book"></i>
                        <span>Trainers Management</span>
                    </a>
                    <ul class="sub">
                        <li><a href="{!! url('trainers_list') !!}">Trainers List</a></li>
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-book"></i>
                        <span>Workout/Exercise</span>
                    </a>
                    <ul class="sub">
                        <li><a href="{!! url('we_yoga') !!}">For Yoga</a></li>
                        <li><a href="{!! url('we_weightlift') !!}">For Weightlifting</a></li>
                    </ul>
                </li>

                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-book"></i>
                        <span>Goals</span>
                    </a>
                    <ul class="sub">
                        <li><a href="{!! url('goals_list') !!}">Goals List</a></li>
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-book"></i>
                        <span>Trainer Requests</span>
                    </a>
                    <ul class="sub">
                        <li><a href="{!! url('trainer_request_list') !!}">Trainer Request List</a></li>
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
<!-- morris JavaScript -->  
<!-- calendar -->
    <script type="text/javascript" src="js/monthly.js"></script>
    <!-- //calendar -->
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable();
        } );
    </script>
</body>
</html>
