<!DOCTYPE html>
<html lang="en">
    @include('layout.head')
<body class="nav-fixed">
    @include('layout.topnav')
    <div id="layoutSidenav">
        @include('layout.sidenav')
        <div id="layoutSidenav_content">
            <main>
                <div class="container-xl px-4 mt-5">
                    <!-- Custom page header alternative example-->
                    <div class="d-flex justify-content-between align-items-sm-center flex-column flex-sm-row mb-4">
                        <div class="me-4 mb-3 mb-sm-0">
                            <h1 class="mb-0">Vouchers</h1>
                        </div>
                    </div>
                    <!-- Illustration dashboard card example-->
                    <div class="card mb-4 mt-5">
                        <div class="card-body p-5">
                            <div class="table-responsive">
                                <table class="table" id="table">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Description</th>
                                            <th>Min. Order Price</th>
                                            <th>Value</th>
                                            <th>Is Percent?</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- s will be populated here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>                    
                </div>
            </main>
            @include('layout.footer')
        </div>
    </div>

    {{-- modals --}}

    @include('layout.scripts')

    <script>
        $(document).ready(function () {
            // Initialize the DataTable
            const token = localStorage.getItem('token'); // Retrieve token from local storage

            const table = $('#table').DataTable({
                ajax: {
                    url: '/api/my-vouchers', // Replace with your API endpoint
                    type: 'GET',
                    dataSrc: 'vouchers', // Since the response is an array of objects
                    beforeSend: function (xhr) {
                        if (token) {
                            xhr.setRequestHeader('Authorization', `Bearer ${token}`);
                        }
                    }
                },
                columns: [
                    { data: 'code' },
                    { data: 'description' },
                    { data: 'min_order_price' },
                    { data: 'value' },
                    { 
                        data: 'is_percent',
                        render: function(data) {
                            return data ? 'Yes' : 'No'; 
                        }
                    },
                ]
            });
    
            // Refresh table after actions (optional)
            function refreshTable() {
                table.ajax.reload();
            }
        });
    </script>
    
</body>
</html>