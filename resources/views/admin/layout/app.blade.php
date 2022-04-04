<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Asbab | Admintrator</title>
    <link rel="stylesheet" href="{{ asset('administrator/assets/bootstrap/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('administrator/assets/font-awesome/css/font-awesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('administrator/common.css') }}" />
    @yield('css')
</head>

<body>
    <section id="container">
        <header class="header bg-white">
            <div class="sidebar-toggle-box">
                <div class="fa fa-bars"></div>
            </div>

            <a href="https://asbab.dev.com/admin" class="logo">Asbab<span>FNT</span></a>

            <div class="nav notify-row" id="top_menu">
                <ul class="nav top-menu">
                    <li id="header_inbox_bar" class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <i class="fa fa-envelope-o"></i>
                            <span class="badge bg-important">5</span>
                        </a>
                        <ul class="dropdown-menu extended inbox">
                            <div class="notify-arrow notify-arrow-red"></div>
                            <li>
                                <p class="red">You have 5 new messages</p>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="photo"><img alt="avatar"
                                            src="{{ asset('administrator/images/avata/avatar-mini.jpg') }}"></span>
                                    <div class="inbox-contain">
                                        <span class="subject">
                                            <span class="from">Jonathan Smith</span>
                                            <span class="time">Just now</span>
                                        </span>
                                        <span class="message">
                                            Hello, this is an example msg.
                                        </span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="photo"><img alt="avatar"
                                            src="{{ asset('administrator/images/avata/avatar-mini2.jpg') }}"></span>
                                    <div class="inbox-contain">
                                        <span class="subject">
                                            <span class="from">Jhon Doe</span>
                                            <span class="time">10 mins</span>
                                        </span>
                                        <span class="message">
                                            Hi, Jhon Doe Bhai how are you ?
                                        </span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="photo"><img alt="avatar"
                                            src="{{ asset('administrator/images/avata/avatar-mini3.jpg') }}"></span>
                                    <div class="inbox-contain">
                                        <span class="subject">
                                            <span class="from">Jason Stathum</span>
                                            <span class="time">3 hrs</span>
                                        </span>
                                        <span class="message">
                                            This is awesome dashboard.
                                        </span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span class="photo"><img alt="avatar"
                                            src="{{ asset('administrator/images/avata/avatar-mini4.jpg') }}"></span>
                                    <div class="inbox-contain">
                                        <span class="subject">
                                            <span class="from">Jondi Rose</span>
                                            <span class="time">Just now</span>
                                        </span>
                                        <span class="message">
                                            Hello, this is metrolab
                                        </span>
                                    </div>
                                </a>
                            </li>
                            <li>
                                <a href="#">See all messages</a>
                            </li>
                        </ul>
                    </li>
                    <li id="header_notification_bar" class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">

                            <i class="fa fa-bell-o"></i>
                            <span class="badge bg-warning">7</span>
                        </a>
                        <ul class="dropdown-menu extended notification">
                            <div class="notify-arrow notify-arrow-yellow"></div>
                            <li>
                                <p class="yellow">You have 7 new notifications</p>
                            </li>
                            <li>
                                <a href="#">
                                    <span>
                                        <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                                        Server #3 overloaded.
                                    </span>
                                    <span class="small italic">34 mins</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span>
                                        <span class="label label-warning"><i class="fa fa-bell"></i></span>
                                        Server #10 not respoding.
                                    </span>
                                    <span class="small italic">1 Hours</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span>
                                        <span class="label label-danger"><i class="fa fa-bolt"></i></span>
                                        Database overloaded 24%.
                                    </span>
                                    <span class="small italic">4 hrs</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span>
                                        <span class="label label-success"><i class="fa fa-plus"></i></span>
                                        New user registered.
                                    </span>
                                    <span class="small italic">Just now</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">
                                    <span>
                                        <span class="label label-info"><i class="fa fa-bullhorn"></i></span>
                                        Application error.
                                    </span>
                                    <span class="small italic">10 mins</span>
                                </a>
                            </li>
                            <li>
                                <a href="#">See all notifications</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>

            <div class="top-nav ">
                <ul class="nav pull-right top-menu">
                    <li>
                        <input type="text" class="form-control search" placeholder="Search" />
                    </li>
                    <li class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <img alt="" src="{{ auth()->user()->profile_photo_path }}">
                            <span class="username">{{ auth()->user()->name }}</span>
                            <b class="caret"></b>
                        </a>

                        <ul class="dropdown-menu extended logout">
                            <div class="log-arrow-up"></div>
                            <li><a href="#"><i class=" fa fa-suitcase"></i>Profile</a></li>
                            <li><a href="#"><i class="fa fa-comments"></i>
                                    Messengers</a></li>
                            <li><a href="#"><i class="fa fa-bell-o"></i> Notification</a></li>
                            <li><a href="{{ route('admin.logout') }}"><i class="fa fa-key"></i> Log Out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </header>
        <aside>
            <div id="sidebar" class="nav-collapse">
                <ul class="sidebar-menu" id="nav-accordion">
                    <li class="{{ request()->is('admin') ? 'active' : '' }}">
                        <a href="{{ route('admin') }}">
                            <i class="fa fa-dashboard"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li
                        class="sidebar-parent {{ request()->is('admin/user*') || request()->is('admin/customer*') ? 'active' : '' }}">
                        <a href="#" class="flex-between"><span><i class="fa fa-users"></i><span>Users</span></span><span
                                class="sidebar-icon-adjq plus"></span></a>
                        <ul
                            class="sidebar-sub {{ request()->is('admin/user*') || request()->is('admin/customer*') ? '' : 'd-none' }}">
                            <li>
                                <a class="{{ request()->is('admin/user*') ? 'active' : '' }}"
                                    href="{{ route('admin.user.index') }}">Employees</a>
                            </li>
                            <li>
                                <a class="{{ request()->is('admin/customer*') ? 'active' : '' }}"
                                    href="{{ route('admin.customer.index') }}">Customers</a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ request()->is('admin/category*') ? 'active' : '' }}">
                        <a href="{{ route('admin.category.index') }}">
                            <i class="fa fa-sitemap"></i>
                            <span>Categories</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/product*') ? 'active' : '' }}">
                        <a href="{{ route('admin.product.index') }}">
                            <i class="fa fa-shopping-cart"></i>
                            <span>Products</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/news*') ? 'active' : '' }}">
                        <a href="{{ route('admin.news.index') }}">
                            <i class="fa fa-book"></i>
                            <span>News</span>
                        </a>
                    </li>
                    <li
                        class="sidebar-parent {{ request()->is('admin/comment*') ? 'active' : '' }}">
                        <a href="#" class="flex-between"><span><i class="fa fa-comments"></i><span>Comments</span></span><span
                                class="sidebar-icon-adjq plus"></span></a>
                        <ul
                            class="sidebar-sub {{ request()->is('admin/comment*') ? '' : 'd-none' }}">
                            <li>
                                <a class="{{ request()->is('admin/comment/product*') ? 'active' : '' }}"
                                    href="{{ route('admin.comment.product') }}">Products</a>
                            </li>
                            <li>
                                <a class="{{ request()->is('admin/comment/news*') ? 'active' : '' }}"
                                    href="{{ route('admin.comment.news') }}">News</a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ request()->is('admin/slider*') ? 'active' : '' }}">
                        <a href="{{ route('admin.slider.index') }}">
                            <i class="fa fa-film"></i>
                            <span>Sliders</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/order*') ? 'active' : '' }}">
                        <a href="{{ route('admin.order.index') }}">
                            <i class="fa fa-file-text-o"></i>
                            <span>Orders</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/coupon*') ? 'active' : '' }}">
                        <a href="{{ route('admin.coupon.index') }}">
                            <i class="fa fa-gift"></i>
                            <span>Cupons</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/delivery*') ? 'active' : '' }}">
                        <a href="{{ route('admin.delivery.index') }}">
                            <i class="fa fa-truck"></i>
                            <span>Delivery</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/brand*') ? 'active' : '' }}">
                        <a href="{{ route('admin.brand.index') }}">
                            <i class="fa fa-link"></i>
                            <span>Brands</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/permission*') ? 'active' : '' }}">
                        <a href="{{ route('admin.permission.create') }}">
                            <i class="fa fa-gavel"></i>
                            <span>Permissions</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/role*') ? 'active' : '' }}">
                        <a href="{{ route('admin.role.index') }}">
                            <i class="fa fa-bookmark-o"></i>
                            <span>Roles</span>
                        </a>
                    </li>
                    <li class="{{ request()->is('admin/setting*') ? 'active' : '' }}">
                        <a href="{{ route('admin.setting.index') }}">
                            <i class="fa fa-cogs"></i>
                            <span>Setting</span>
                        </a>
                    </li>
                </ul>
            </div>
        </aside>
        @yield('content')
        <footer class="site-footer">
            <div class="text-center">
                2022 &copy; AsbabFurniture by QuocCuong.
            </div>
        </footer>
    </section>

    <script src="{{ asset('administrator/assets/jquery/jquery-3.5.0.min.js') }}"></script>
    <script src="{{ asset('administrator/assets/bootstrap/bootstrap.min.js') }}"></script>
    @yield('js')
</body>

</html>
