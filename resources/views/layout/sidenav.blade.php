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
                <a class="nav-link role-admin" href="/users">
                    <div class="nav-link-icon"><i data-feather="users"></i></div>
                    Users
                </a>
                <a class="nav-link role-admin" href="/locations">
                    <div class="nav-link-icon"><i data-feather="map"></i></div>
                    Location
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

        // Display user name if available
        if (user && user.name) {
            $('.sidenav-footer-title').text('Logged in as: '+user.name);
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
    });

</script>


