<div class="vertical-menu">

    <div data-simplebar class="h-100">

        @php
            $role = Auth::user()->role;
        @endphp

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                <li>
                    <a href="{{ route('dashboard') }}" class="waves-effect">
                        <i class="ri-dashboard-line"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                @if ($role == '1')
                    <li>
                        <a href="javascript: void(0);" class="has-arrow waves-effect">
                            <i class="ri-shield-user-fill"></i>
                            <span>Manage Employees</span>
                        </a>
                        <ul class="sub-menu" aria-expanded="false">
                            <li><a href="{{ route('location.all') }}">Locations</a></li>
                            <li><a href="{{ route('employee.all') }}">Employees</a></li>
                        </ul>

                    </li>
                @endif

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-shield-user-fill"></i>
                        <span>Customers</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('customer.all') }}">All Customers</a></li>
                        {{-- <li><a href="email-read.html">Read Email</a></li> --}}
                    </ul>
                </li>


                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-shield-user-fill"></i>
                        <span>Services</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('service.all') }}">All Services</a></li>
                        {{-- <li><a href="email-read.html">Read Email</a></li> --}}
                    </ul>
                </li>

                <li class="menu-title">Consultation</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-shield-user-fill"></i>
                        <span>Consultation</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('consultation.all') }}">All Consultations</a></li>
                        {{-- <li><a href="email-read.html">Read Email</a></li> --}}
                    </ul>
                </li>


                <li class="menu-title">Prescriptions</li>

                <li>

                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-shield-user-fill"></i>
                        <span>Prescription</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('prescription.all') }}">All Prescriptions</a></li>
                        {{-- <li><a href="email-read.html">Read Email</a></li> --}}
                    </ul>

                </li>

                <li class="menu-title">Product</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-hotel-fill"></i>
                        <span>Manage Products</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('supplier.all') }}">Suppliers</a></li>
                        {{-- <li><a href="{{ route('unit.all') }}">Units</a></li> --}}
                        <li><a href="{{ route('category.all') }}">Categories</a></li>
                        <li><a href="{{ route('product.all') }}">Products</a></li>
                    </ul>


                </li>

                <li class="menu-title">Purchase</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-shopping-cart-fill"></i>
                        <span>Manage Purchases</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('purchase.all') }}">All Purchases</a></li>
                        {{-- <li><a href="{{ route('purchase.pending') }}">Purchase Approval</a></li> --}}
                        <li><a href="{{ route('daily.purchase.report') }}">Daily Purchase Report</a></li>
                    </ul>

                </li>


                <li class="menu-title">Invoice</li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class=" ri-edit-box-fill"></i>
                        <span>Manage Invoice</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="{{ route('invoice.all') }}">All Invoices</a></li>
                        <li><a href="{{ route('invoice.pending.list') }}">Invoice Approval</a></li>
                        <li><a href="{{ route('daily.invoice.report') }}">Daily Invoice Report</a></li>
                    </ul>

                </li>





                <li class="menu-title">Pages</li>

                {{-- <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-account-circle-line"></i>
                        <span>Authentication</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="auth-login.html">Login</a></li>
                        <li><a href="auth-register.html">Register</a></li>
                        <li><a href="auth-recoverpw.html">Recover Password</a></li>
                        <li><a href="auth-lock-screen.html">Lock Screen</a></li>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class="ri-profile-line"></i>
                        <span>Utility</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="false">
                        <li><a href="pages-starter.html">Starter Page</a></li>
                        <li><a href="pages-timeline.html">Timeline</a></li>
                        <li><a href="pages-directory.html">Directory</a></li>
                        <li><a href="pages-invoice.html">Invoice</a></li>
                        <li><a href="pages-404.html">Error 404</a></li>
                        <li><a href="pages-500.html">Error 500</a></li>
                    </ul>
                </li> --}}



            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
