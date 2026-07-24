<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>BIRD (e-TIMS)</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link href="{{ asset('assets/images/logo_white.png') }}" rel="icon" />

    <!-- Fonts and icons -->
    <script src="{{ url('/admin') }}/assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
        WebFont.load({
            google: {
                families: ["Public Sans:300,400,500,600,700"]
            },
            custom: {
                families: [
                    "Font Awesome 5 Solid",
                    "Font Awesome 5 Regular",
                    "Font Awesome 5 Brands",
                    "simple-line-icons",
                ],
                urls: ["{{ url('/admin') }}/assets/css/fonts.min.css"],
            },
            active: function() {
                sessionStorage.fonts = true;
            },
        });
    </script>

    <!-- CSS Files -->
    <link rel="stylesheet" href="{{ url('/admin') }}/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{ url('/admin') }}/assets/css/plugins.min.css" />
    <link rel="stylesheet" href="{{ url('/admin') }}/assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <link rel="stylesheet" href="{{ url('/admin') }}/assets/css/demo.css" />
</head>

<body>
    <div class="wrapper">

        <!-- Sidebar -->
        <div class="sidebar" data-background-color="white">

            <div class="sidebar-logo">
                <!-- Logo Header -->
                <div class="logo-header" data-background-color="dark">
                    <a href="{{ route('admin.dashboard') }}" class="logo">
                        <img src="{{ asset('assets/images/logo_white.png') }}" alt="" class="navbar-brand"
                            height="70" />
                    </a>

                    <div class="nav-toggle">
                        <button class="btn btn-toggle toggle-sidebar">
                            <i class="gg-menu-right"></i>
                        </button>
                        <button class="btn btn-toggle sidenav-toggler">
                            <i class="gg-menu-left"></i>
                        </button>
                    </div>
                    <button class="topbar-toggler more">
                        <i class="gg-more-vertical-alt"></i>
                    </button>
                </div>
                <!-- End Logo Header -->
            </div>

            <div class="sidebar-wrapper scrollbar scrollbar-inner">
                <div class="sidebar-content">
                    <ul class="nav nav-secondary">

                        @php
                            $admin = Auth::guard('admin')->user();
                            $isHostelAdmin = in_array(strtolower($admin->role), ['hostel', 'hostel_admin']);
                        @endphp
                        {{-- Dashboard --}}
                        @if ($admin->hasAccess('dashboard') || $isHostelAdmin)
                            <li class="nav-item active">
                                <a href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-home"></i>
                                    <p>Dashboard</p>
                                </a>
                            </li>
                        @endif

                        {{-- Static Dashboard --}}
                        @if ($admin->hasAccess('static_dashboard') && !$isHostelAdmin)
                            <li class="nav-item">
                                <a href="{{ route('admin.static.dashboard') }}" class="nav-link">
                                    <i class="fas fa-chart-pie"></i>
                                    <p>Static Dashboard</p>
                                </a>
                            </li>
                        @endif

                        {{-- Add Faculty --}}
                        @if ($admin->hasAccess('add_faculty'))
                            <li class="nav-item">
                                <a href="{{ route('user.index') }}">
                                    <i class="fas fa-chalkboard-teacher"></i>
                                    <p>Add Faculty</p>
                                </a>
                            </li>
                        @endif

                        {{-- Master (dropdown) --}}
                        @if ($admin->hasAccess('master'))
                            <li class="nav-item">
                                <a data-bs-toggle="collapse" href="#sidebarMaster">
                                    <i class="fas fa-puzzle-piece"></i>
                                    <p>Master</p>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse" id="sidebarMaster">
                                    <ul class="nav nav-collapse">
                                        <li><a href="{{ route('user.adduser') }}"><span class="sub-item">Add
                                                    user</span></a></li>
                                        <li><a href="{{ route('agency.type.create') }}"><span class="sub-item">Agency
                                                    Type</span></a></li>
                                        <li><a href="{{ route('agency.create') }}"><span
                                                    class="sub-item">Agency</span></a></li>
                                        <li><a href="{{ route('index.class') }}"><span class="sub-item">Class
                                                    Room</span></a></li>
                                        <li><a href="{{ route('admin.department') }}"><span class="sub-item">Head
                                                    Office Department</span></a></li>
                                        <li><a href="{{ route('admin.group') }}"><span class="sub-item">Add
                                                    Verticals</span></a></li>
                                        <li><a href="{{ route('admin.sponsor') }}"><span class="sub-item">Add
                                                    Sponsorship</span></a></li>
                                        <li><a href="{{ route('admin.guest') }}"><span class="sub-item">Add Guest
                                                    Faculty</span></a></li>
                                        <li><a href="{{ route('feedback.master') }}"><span class="sub-item">Add
                                                    Feedback</span></a></li>
                                        <li><a href="{{ route('admin.feedback') }}"><span class="sub-item">Feedback
                                                    Management</span></a></li>
                                    </ul>
                                </div>
                            </li>
                        @endif

                        {{-- Programme (dropdown) --}}
                        @if ($admin->hasAccess('programme'))
                            <li class="nav-item">
                                <a data-bs-toggle="collapse" href="#sidebarMaster2">
                                    <i class="fas fa-graduation-cap"></i>
                                    <p>Programme</p>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse" id="sidebarMaster2">
                                    <ul class="nav nav-collapse">
                                        <li><a href="{{ route('programmeManagement.list') }}"><span
                                                    class="sub-item">Add/Manage Programme</span></a></li>
                                        <li><a href="{{ route('faculity.session') }}"><span class="sub-item">Add/Manage
                                                    Programme Session</span></a></li>
                                        <li><a href="{{ route('day-to-day-session') }}"><span class="sub-item">View Day
                                                    to Day Session</span></a></li>
                                    </ul>
                                </div>
                            </li>
                        @endif

                        {{-- Question Management (dropdown) --}}
                        @if ($admin->hasAccess('question_management'))
                            <li class="nav-item">
                                <a data-bs-toggle="collapse" href="#sidebarMaster3">
                                    <i class="fas fa-question-circle"></i>
                                    <p>Question Management</p>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse" id="sidebarMaster3">
                                    <ul class="nav nav-collapse">
                                        <li><a href="{{ route('admin.addQuestion') }}"><span class="sub-item">Add
                                                    Question</span></a></li>
                                        <li><a href="{{ route('setpaper.view') }}"><span class="sub-item">Set
                                                    Paper</span></a></li>
                                        <li><a href="{{ route('view.selected.question') }}"><span
                                                    class="sub-item">Selected Questions</span></a></li>
                                        <li><a href="{{ route('preview.question.paper') }}"><span
                                                    class="sub-item">Preview Questions Paper</span></a></li>
                                        <li><a href="{{ route('admin.programme-exam-qr-codes') }}"><span
                                                    class="sub-item">Program QR Codes</span></a></li>
                                    </ul>
                                </div>
                            </li>
                        @endif

                        {{-- Nomination (dropdown) --}}
                        @if ($admin->hasAccess('nomination'))
                            <li class="nav-item">
                                <a data-bs-toggle="collapse" href="#sidebarMaster4">
                                    <i class="fas fa-user-plus"></i>
                                    <p>Nomination</p>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse" id="sidebarMaster4">
                                    <ul class="nav nav-collapse">
                                        <li><a href="{{ route('nomination.list') }}"><span
                                                    class="sub-item">Add/Manage Nomination</span></a></li>
                                        <li><a href="{{ route('nominations.search') }}"><span class="sub-item">View
                                                    Nomination</span></a></li>
                                    </ul>
                                </div>
                            </li>
                        @endif

                        {{-- Budget (dropdown) --}}
                        @if ($admin->hasAccess('budget'))
                            <li class="nav-item">
                                <a data-bs-toggle="collapse" href="#sidebarMaster5">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <p>Budget</p>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse" id="sidebarMaster5">
                                    <ul class="nav nav-collapse">
                                        <li><a href="{{ route('budget.expenditure.view') }}"><span
                                                    class="sub-item">Expenditure</span></a></li>
                                        <li><a href="{{ route('menus.selectMultiple') }}"><span
                                                    class="sub-item">Budget</span></a></li>
                                    </ul>
                                </div>
                            </li>
                        @endif

                        {{-- Reports (dropdown) --}}
                        @if ($admin->hasAccess('reports'))
                            <li class="nav-item">
                                <a data-bs-toggle="collapse" href="#sidebarMaster6">
                                    <i class="fas fa-file-alt"></i>
                                    <p>Reports</p>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse" id="sidebarMaster6">
                                    <ul class="nav nav-collapse">
                                        <li><a href="{{ route('session-wise-feedback-report') }}"><span
                                                    class="sub-item">Session Wise Feedback Report</span></a></li>
                                        <li><a href="{{ route('participants.reports') }}"><span
                                                    class="sub-item">Participant</span></a></li>
                                        <li><a href="{{ route('rating.reports') }}"><span
                                                    class="sub-item">Rating</span></a></li>
                                        <li><a href="{{ route('marks.reports') }}"><span class="sub-item">Entry Test
                                                    Marks</span></a></li>
                                        <li><a href="{{ route('marks.exits.reports') }}"><span class="sub-item">Exist
                                                    Test Marks</span></a></li>
                                        <li><a href="{{ route('attendence.reports') }}"><span
                                                    class="sub-item">Attendence</span></a></li>
                                        <li><a href="{{ route('view.feedback.reports') }}"><span
                                                    class="sub-item">Feedback</span></a></li>
                                        <li><a href="{{ route('room.occupancy.report') }}"><span
                                                    class="sub-item">Room Occupancy</span></a></li>
                                    </ul>
                                </div>
                            </li>
                        @endif

                        {{-- Hostel-specific block --}}
                        {{-- Hostel-specific block --}}
                        @if ($isHostelAdmin)
                            {{-- Hostel Master (dropdown) — unique id: sidebarHostelMaster --}}
                            <li class="nav-item">
                                <a data-bs-toggle="collapse" href="#sidebarHostelMaster">
                                    <i class="fas fa-puzzle-piece"></i>
                                    <p>Master</p>
                                    <span class="caret"></span>
                                </a>
                                <div class="collapse" id="sidebarHostelMaster">
                                    <ul class="nav nav-collapse">
                                        <li><a href="{{ route('rooms.block') }}"><span class="sub-item">Manage
                                                    Blocks</span></a></li>
                                        <li><a href="{{ route('rooms.type') }}"><span class="sub-item">Room Type
                                                    Add</span></a></li>
                                        <li><a href="{{ route('room.list') }}"><span class="sub-item">Manage Room
                                                    Number</span></a></li>
                                        <li><a href="{{ route('rooms.assignNumber') }}"><span class="sub-item">Update
                                                    Room Number</span></a></li>
                                    </ul>
                                </div>
                            </li>

                            {{-- Manage Checkouts --}}
                            <li class="nav-item">
                                <a href="{{ route('hostel.attendence') }}">
                                    <i class="fas fa-home"></i>
                                    <p>Manage Checkouts</p>
                                </a>
                            </li>

                            {{-- Room Allotment --}}
                            <li class="nav-item">
                                <a href="{{ route('room.assign') }}">
                                    <i class="fas fa-bed"></i>
                                    <p>Room Allotment</p>
                                </a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-power-off"></i>
                                <p>Logout</p>
                            </a>
                            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </li>

                    </ul>
                </div>
            </div>

        </div>
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="{{ url('/admin-panel/dashboard') }}" class="logo">
                            <img src="" alt="navbar brand" class="navbar-brand" height="70" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>
                <!-- Navbar Header -->
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">

                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                                    aria-expanded="false">
                                    <div class="avatar-sm">
                                        <img src="{{ asset('assets/images/logo_white.png') }}" alt="..."
                                            class="avatar-img rounded-circle" />
                                    </div>
                                    <span class="profile-username">
                                        <span class="op-7">Hi,</span>
                                        {{ Auth::guard('faculty')->check() ? Auth::guard('faculty')->user()->name : 
                                        (Auth::guard('admin')->check() ? Auth::guard('admin')->user()->name : 'Guest') }}
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <a class="dropdown-item" href="#"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                Logout
                                            </a>
                                            <form id="logout-form" action="{{ route('admin.logout') }}"
                                                method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </li>
                                    </div>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
                <!-- End Navbar -->
            </div>
            <div id="loader"
                style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(255,255,255,0.6); z-index:9999; text-align:center; padding-top:200px;">
                <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
            </div>
