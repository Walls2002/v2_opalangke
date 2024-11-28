<footer class="footer-admin mt-auto footer-light">
    <div class="container-xl px-4">
        <div class="row">
            <div class="col-md-6 small">Copyright &copy; Palengke 2024</div>
        </div>
    </div>
</footer>

<script>
    $(document).ready(function() {
        // Check the value of "status" in local storage
        var token = localStorage.getItem('token');

        if (token === null) {
            // Redirect to index.php if there is no value
            window.location.href = '/';
        }
    });
</script>