<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>
        @section('title')
            | Josh Admin Template
        @show
    </title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    {{--CSRF Token--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- global css -->

    <link rel="stylesheet" href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/css/app.css?t=' . time()) }}"/>
    <link rel="stylesheet" href="{{ asset('/vendor/' . $group_name . '/' . $skin . '/css/global.css?t=' . time()) }}"/>
    <!-- font Awesome -->

    <!-- end of global css -->
    <!--page level css-->
    @yield('header_styles')
            <!--end of page level css-->

<body class="skin-josh">
<header class="header">
    <a href="{{ route('backend.homepage') }}" class="logo">
        <img src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/img/logo.png') }}" alt="logo">
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <div>
            <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                <div class="responsive_nav"></div>
            </a>
        </div>

        <div class="navbar-right">
            <ul class="nav navbar-nav">
                @include('includes._messages')
                @include('includes._notifications')
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="{!! asset('/vendor/' . $group_name . '/' . $skin . '/images/authors/no_avatar.jpg') !!}" alt="img" height="35px" width="35px"
                                 class="img-circle img-responsive pull-left"/>
                        <div class="riot">
                            <div>
                                <p class="user_name_max">{{ $USER_LOGGED->contact_name }}</p>
                                <span>
                                    <i class="caret"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header bg-light-blue">
                            <img src="{!! asset('/vendor/' . $group_name . '/' . $skin . '/images/authors/no_avatar.jpg') !!}" alt="img" height="35px" width="35px"
                                 class="img-circle img-responsive pull-left"/>

                            {{--@elseif($USER_LOGGED->gender === "male")--}}
                                {{--<img src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/images/authors/avatar3.png') }}" alt="img" height="35px" width="35px"--}}
                                     {{--class="img-circle img-responsive pull-left"/>--}}

                            {{--@elseif($USER_LOGGED->gender === "female")--}}
                                {{--<img src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/images/authors/avatar5.png') }}" alt="img" height="35px" width="35px"--}}
                                     {{--class="img-circle img-responsive pull-left"/>--}}
                            {{--@else--}}
                                {{--<img src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/images/authors/no_avatar.jpg') }}" alt="img" height="35px" width="35px"--}}
                                     {{--class="img-circle img-responsive pull-left"/>--}}
                            {{--@endif--}}
                            {{--<p class="topprofiletext">{{ $USER_LOGGED->contact_name }}</p>--}}
                        </li>
                        <!-- Menu Body -->
                        <li>
                            <a href="{{ URL::route('adtech.core.user.show',$USER_LOGGED_ID) }}">
                                <i class="livicon" data-name="user" data-s="18"></i>
                                My Profile
                            </a>
                        </li>
                        <li role="presentation"></li>
                        <li>
                            <a href="{{ route('adtech.core.user.update', $USER_LOGGED_ID) }}">
                                <i class="livicon" data-name="gears" data-s="18"></i>
                                Account Settings
                            </a>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ URL::route('adtech.core.auth.logout',$USER_LOGGED_ID) }}">
                                    <i class="livicon" data-name="lock" data-size="16" data-c="#555555" data-hc="#555555" data-loop="true"></i>
                                    Lock
                                </a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ URL::route('adtech.core.auth.logout') }}">
                                    <i class="livicon" data-name="sign-out" data-s="15"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>

        @include('includes._menu_top')
    </nav>
</header>
<div class="wrapper ">
    <!-- Left side column. contains the logo and sidebar -->
    <aside class="left-side ">
        <section class="sidebar ">
            <div class="page-sidebar  sidebar-nav">
                <div class="nav_icons">
                    <ul class="sidebar_threeicons">
                        <li>
                            <a href="{{ URL::to('admin/advanced_tables') }}">
                                <i class="livicon" data-name="table" title="Advanced tables" data-loop="true"
                                   data-color="#418BCA" data-hc="#418BCA" data-s="25"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('admin/tasks') }}">
                                <i class="livicon" data-name="list-ul" title="Tasks" data-loop="true"
                                   data-color="#e9573f" data-hc="#e9573f" data-s="25"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('admin/gallery') }}">
                                <i class="livicon" data-name="image" title="Gallery" data-loop="true"
                                   data-color="#F89A14" data-hc="#F89A14" data-s="25"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('admin/users') }}">
                                <i class="livicon" data-name="user" title="Users" data-loop="true"
                                   data-color="#6CC66C" data-hc="#6CC66C" data-s="25"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="clearfix"></div>
                <!-- BEGIN SIDEBAR MENU -->
                @include('includes._left_menu')
                <!-- END SIDEBAR MENU -->
            </div>
        </section>
    </aside>
    <aside class="right-side">

        <!-- Notifications -->
        <div id="notific">
        @include('includes.notifications')
        </div>

                <!-- Content -->
        @yield('content')

    </aside>
    <!-- right-side -->
</div>
<a id="back-to-top" href="#" class="btn btn-primary btn-lg back-to-top" role="button" title="Return to top"
   data-toggle="tooltip" data-placement="left">
    <i class="livicon" data-name="plane-up" data-size="18" data-loop="true" data-c="#fff" data-hc="white"></i>
</a>
<!-- global js -->
<script src="{{ asset('/vendor/' . $group_name . '/' . $skin . '/js/app.js?t=').time() }}"></script>
<!-- end of global js -->
<!-- begin page level js -->
@yield('footer_scripts')
@yield('footer_scripts_more')
<!-- end page level js -->
</body>
</html>
