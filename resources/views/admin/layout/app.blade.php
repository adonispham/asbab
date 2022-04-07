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

            <a href="#" class="logo">Asbab<span>FNT</span></a>
            <div class="nav notify-row" id="top_menu">
                <ul class="nav top-menu">
                    <li id="header_inbox_bar" class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <i class="fa fa-comments"></i>
                            <span
                                class="badge bg-important">{{ \App\Models\Message::where('read', 0)->get()->count() }}</span>
                        </a>
                        <ul class="dropdown-menu extended inbox">
                            <div class="notify-arrow notify-arrow-red"></div>
                            <div class="notify-inbox-count">
                                <p class="red">You have
                                    {{ \App\Models\Message::where('read', 0)->get()->count() }} new messages</p>
                            </div>
                            <div class="notify-inbox-content"></div>
                            <a class="notify-inbox-btn" href="{{ route('admin.chat.index') }}">See all messages</a>
                        </ul>
                    </li>
                    {{--  <li id="header_notification_bar" class="dropdown">
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <i class="fa fa-bell-o"></i>
                            <span class="badge bg-warning">7</span>
                        </a>
                        <ul class="dropdown-menu extended notification">
                            <div class="notify-arrow notify-arrow-yellow"></div>
                            <div class="notify-inbox-count">
                                <p class="yellow">You have 7 new notifications</p>
                            </div>
                            <div class="notify-inbox-content">
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
                                    <a href="#">
                                        <span>
                                            <span class="label label-info"><i class="fa fa-bullhorn"></i></span>
                                            Application error.
                                        </span>
                                        <span class="small italic">10 mins</span>
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
                                    <a href="#">
                                        <span>
                                            <span class="label label-info"><i class="fa fa-bullhorn"></i></span>
                                            Application error.
                                        </span>
                                        <span class="small italic">10 mins</span>
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
                            </div>
                            <a class="notify-inbox-btn" href="{{ route('admin.chat.index') }}">See all
                                notifications</a>
                        </ul>
                    </li>  --}}
                </ul>
            </div>

            <div class="top-nav">
                <ul class="nav pull-right top-menu">
                    <li>
                        <input type="text" class="form-control search" placeholder="Search" />
                    </li>
                    <li>
                        <span class="user-login">
                            <img alt="" src="{{ asset(auth()->user()->avatar) }}">
                            <span class="username">
                                {{ auth()->user()->name }}
                                <a href="{{ route('admin.logout') }}"><i class="fa fa-sign-in"></i></a>
                            </span>
                        </span>
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
                    @if (auth()->user()->Can('list customer') || auth()->user()->Can('list employee'))
                        <li
                            class="sidebar-parent {{ request()->is('admin/employee*') || request()->is('admin/customer*') ? 'active' : '' }}">
                            <a href="#" class="flex-between"><span><i
                                        class="fa fa-users"></i><span>Users</span></span><span
                                    class="sidebar-icon-adjq plus"></span></a>
                            <ul
                                class="sidebar-sub {{ request()->is('admin/employee*') || request()->is('admin/customer*') ? '' : 'd-none' }}">
                                @can('list employee')
                                    <li>
                                        <a class="{{ request()->is('admin/employee*') ? 'active' : '' }}"
                                            href="{{ route('admin.employee.index') }}">Employees</a>
                                    </li>
                                @endcan
                                @can('list customer')
                                    <li>
                                        <a class="{{ request()->is('admin/customer*') ? 'active' : '' }}"
                                            href="{{ route('admin.customer.index') }}">Customers</a>
                                    </li>
                                @endcan
                            </ul>
                        </li>
                    @endif
                    @if (auth()->user()->Can('list category'))
                        <li class="{{ request()->is('admin/category*') ? 'active' : '' }}">
                            <a href="{{ route('admin.category.index') }}">
                                <i class="fa fa-sitemap"></i>
                                <span>Categories</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->Can('list product'))
                        <li class="{{ request()->is('admin/product*') ? 'active' : '' }}">
                            <a href="{{ route('admin.product.index') }}">
                                <i class="fa fa-shopping-cart"></i>
                                <span>Products</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->Can('list news'))
                        <li class="{{ request()->is('admin/news*') ? 'active' : '' }}">
                            <a href="{{ route('admin.news.index') }}">
                                <i class="fa fa-book"></i>
                                <span>News</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->Can('list comment'))
                        <li class="sidebar-parent {{ request()->is('admin/comment*') ? 'active' : '' }}">
                            <a href="#" class="flex-between"><span><i
                                        class="fa fa-comments"></i><span>Comments</span></span><span
                                    class="sidebar-icon-adjq plus"></span></a>
                            <ul class="sidebar-sub {{ request()->is('admin/comment*') ? '' : 'd-none' }}">
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
                    @endif
                    @if (auth()->user()->Can('list slider'))
                        <li class="{{ request()->is('admin/slider*') ? 'active' : '' }}">
                            <a href="{{ route('admin.slider.index') }}">
                                <i class="fa fa-film"></i>
                                <span>Sliders</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->Can('list chat'))
                        <li class="{{ request()->is('admin/chat*') ? 'active' : '' }}">
                            <a href="{{ route('admin.chat.index') }}">
                                <i class="fa fa-inbox"></i>
                                <span>Message</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->Can('list order'))
                        <li class="{{ request()->is('admin/order*') ? 'active' : '' }}">
                            <a href="{{ route('admin.order.index') }}">
                                <i class="fa fa-file-text-o"></i>
                                <span>Orders</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->Can('list coupon'))
                        <li class="{{ request()->is('admin/coupon*') ? 'active' : '' }}">
                            <a href="{{ route('admin.coupon.index') }}">
                                <i class="fa fa-gift"></i>
                                <span>Cupons</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->Can('list delivery'))
                        <li class="{{ request()->is('admin/delivery*') ? 'active' : '' }}">
                            <a href="{{ route('admin.delivery.index') }}">
                                <i class="fa fa-truck"></i>
                                <span>Delivery</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->Can('list brand'))
                        <li class="{{ request()->is('admin/brand*') ? 'active' : '' }}">
                            <a href="{{ route('admin.brand.index') }}">
                                <i class="fa fa-link"></i>
                                <span>Brands</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->Can('list support'))
                        <li class="{{ request()->is('admin/support*') ? 'active' : '' }}">
                            <a href="{{ route('admin.support.index') }}">
                                <i class="fa fa-question-circle"></i>
                                <span>Supports</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->Can('add permission'))
                        <li class="{{ request()->is('admin/permission*') ? 'active' : '' }}">
                            <a href="{{ route('admin.permission.create') }}">
                                <i class="fa fa-gavel"></i>
                                <span>Permissions</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->Can('list role'))
                        <li class="{{ request()->is('admin/role*') ? 'active' : '' }}">
                            <a href="{{ route('admin.role.index') }}">
                                <i class="fa fa-bookmark-o"></i>
                                <span>Roles</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->Can('list setting'))
                        <li class="{{ request()->is('admin/setting*') ? 'active' : '' }}">
                            <a href="{{ route('admin.setting.index') }}">
                                <i class="fa fa-cogs"></i>
                                <span>Setting</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </aside>
        @yield('content')
        <footer class="site-footer">
            <div class="text-center">
                2021 &copy; AsbabFurniture by BaTruong.
            </div>
        </footer>
    </section>

    <script src="{{ asset('administrator/assets/jquery/jquery-3.5.0.min.js') }}"></script>
    <script src="{{ asset('administrator/assets/bootstrap/bootstrap.min.js') }}"></script>
    <script src="https://js.pusher.com/7.0.3/pusher.min.js"></script>
    @yield('js')
    <script>
        var pusher = new Pusher('af88ad31025c923bf4f8', {
            forceTLS: true,
            cluster: 'ap1'
        });
        var channel = pusher.subscribe('chat-with-admin');
        channel.bind('chat-client', function(data) {
            let countMessageNew = parseInt($('#header_inbox_bar .badge').text());
            $('#header_inbox_bar .badge').text(countMessageNew + 1);
            let activeEl = $('#contacts').find('.contact.active');
            if(activeEl.length) {
                if (activeEl.data('id') == data.user.id) {
                    $(`<li class="replies">
                            <span>
                                <img src="${ document.documentURI.split('admin')[0] + (data.user.avatar !== null ? data.user.avatar : 'images/avatar/default.jpg') }"
                                    alt="" />
                            </span>
                            <p>${ data.message.message }</p>
                        </li>`).appendTo('#frame .content .messages ul');
                    activeEl.find('.preview').text(data.message.message);
                }
            } else {
                $(`<li data-contact="${ data.message.id }" data-id="${ data.user.id }" class="contact">
                    <div class="wrap">
                        <span class="contact-status online"></span>
                        <img src="${ document.documentURI.split('admin')[0] + (data.user.avatar !== null ? data.user.avatar : 'images/avatar/default.jpg') }" alt="" />
                        <div class="meta">
                            <p class="name">${ data.user.name }</p>
                            <p class="preview not-read">${ data.message.message }</p>
                        </div>
                    </div>
                </li>`).appendTo('#contacts ul');
            }
        });
    </script>
</body>

</html>
