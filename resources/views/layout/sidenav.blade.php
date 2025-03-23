<div id="layoutSidenav_nav">
    <nav class="sidenav shadow-right sidenav-light">
        <div class="sidenav-menu">
            <div class="nav accordion" id="accordionSidenav">
                <div class="sidenav-menu-heading">Home</div>
                <a class="nav-link" href="/home">
                    <div class="nav-link-icon"><i data-feather="home"></i></div>
                    Home
                </a>

            <div class="sidenav-menu-heading">Pages</div>
                <!-- admin -->
                <a class="nav-link role-admin" href="/vendors">
                    <div class="nav-link-icon"><i data-feather="users"></i></div>
                    Vendors
                </a>
                <a class="nav-link role-admin" href="/vouchers">
                    <div class="nav-link-icon"><i data-feather="percent"></i></div>
                    Vouchers
                </a>
                <a class="nav-link role-admin" href="/locations">
                    <div class="nav-link-icon"><i data-feather="map"></i></div>
                    Location
                </a>

                {{-- vendor --}}
                <a class="nav-link role-vendor" href="/store">
                    <div class="nav-link-icon"><i data-feather="shopping-bag"></i></div>
                    Store
                </a>

                <a class="nav-link role-vendor" href="/rider">
                    <div class="nav-link-icon"><i data-feather="user"></i></div>
                    Rider
                </a>

                <!-- Sidenav Accordion (Flows)-->
                <a class="nav-link collapsed role-vendor" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseFlows2" aria-expanded="false" aria-controls="collapseFlows2">
                    <div class="nav-link-icon"><i data-feather="check-square"></i></div>
                    Orders
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseFlows2" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="/vendor-order-pending">Pending</a>
                        <a class="nav-link" href="/vendor-order-confirmed">Confirmed</a>
                        <a class="nav-link" href="/vendor-order-delivered">Delivered</a>
                        <a class="nav-link" href="/vendor-order-canceled">Canceled</a>
                    </nav>
                </div>


                {{-- customer --}}
                <a class="nav-link role-customer" href="/cart">
                    <div class="nav-link-icon"><i data-feather="shopping-cart"></i></div>
                    Cart
                </a>
                <!-- Sidenav Accordion (Flows)-->
                <a class="nav-link collapsed role-customer" href="javascript:void(0);" data-bs-toggle="collapse" data-bs-target="#collapseFlows" aria-expanded="false" aria-controls="collapseFlows">
                    <div class="nav-link-icon"><i data-feather="check-square"></i></div>
                    Orders
                    <div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="collapseFlows" data-bs-parent="#accordionSidenav">
                    <nav class="sidenav-menu-nested nav">
                        <a class="nav-link" href="/order-pending">Pending</a>
                        <a class="nav-link" href="/order-confirmed">Confirmed</a>
                        <a class="nav-link" href="/order-delivered">Delivered</a>
                        <a class="nav-link" href="/order-canceled">Canceled</a>
                    </nav>
                </div>

                {{-- rider --}}
                <a class="nav-link role-rider" href="/delivery">
                    <div class="nav-link-icon"><i data-feather="user"></i></div>
                    Delivery
                </a>
            </div>
        </div>
        <!-- Sidenav Footer-->
        <div class="sidenav-footer">
            <div class="sidenav-footer-content">
                <div class="sidenav-footer-subtitle"></div>
                <div class="sidenav-footer-title"></div>
                <div class="sidenav-footer-subtitle sidenav-footer-role">Admin</div>
            </div>
        </div>
    </nav>
</div>

<script>
    $(document).ready(function() {
        // Get the current page URL and set the active link
        const currentPage = window.location.pathname;

        $('.nav-link').each(function() {
            if ($(this).attr('href') === currentPage) {
                $(this).addClass('active');
            }
        });

        const user = JSON.parse(localStorage.getItem('user'));
        const user_type = JSON.parse(localStorage.getItem('user_type'));

        // Display user name if available
        if (user && user.first_name) {
            $('.sidenav-footer-title').text('Logged in as: '+user.first_name);
        }

        if (user && user.role) {
            $('.sidenav-footer-role').text(user.role);
        }

        if (user.role != 'admin') {
            $('.role-admin').addClass('d-none');
        }

        if (user.role != 'vendor') {
            $('.role-vendor').addClass('d-none');
        }

        if (user.role != 'customer') {
            $('.role-customer').addClass('d-none');
        }

        if (user_type != 'rider') {
            $('.role-rider').addClass('d-none');
        }
    });

</script>


