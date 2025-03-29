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
                            <h1 class="mb-0">Products</h1>
                        </div>
                    </div>
                    <!-- Illustration dashboard card example-->
                    <div class="card mb-4 mt-5">
                        <div class="card-body p-5">
                            <div class="table-responsive">
                                <table id="usersTable" class="table">
                                    <thead>
                                        <tr>
                                            <th>Store</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Measurement</th>
                                            <th>Price</th>
                                            <th>Quantity</th>
                                            <th>Category</th>
                                        
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Data will be populated here by DataTables -->
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
        <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createModalLabel">Create New Store</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <!-- Form for creating a new store -->
                    <div class="modal-body">
                        <form id="createForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="product_name" class="form-label">Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="product_name" placeholder="Enter product name" required />
                            </div>
                            
                            <div class="mb-3">
                                <label for="image" class="form-label">Product Image</label>
                                <input type="file" class="form-control" id="image" accept="image/*" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Measurement<span class="text-danger">*</span></label>
                                <select id="measurement" class="form-select" required>
                                    <option value="kilo">Kilo</option>
                                    <option value="piece">Piece</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Price<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="price" placeholder="Enter price" required />
                            </div>

                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="quantity" placeholder="Enter quantity" required />
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Category<span class="text-danger">*</span></label>
                                <select id="category_id" class="form-select" required>
                                    <option value="">Select Category</option>
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Store Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Store</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <!-- Form for editing an existing store -->
                    <div class="modal-body">
                        <form id="editForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="product_name" class="form-label">Name<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_product_name" placeholder="Enter product name" required />
                            </div>
                            
                            <div class="mb-3">
                                <label for="image" class="form-label">Product Image</label>
                                <input type="file" class="form-control" id="edit_image" accept="image/*" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Measurement<span class="text-danger">*</span></label>
                                <select id="edit_measurement" class="form-select" required>
                                    <option value="kilo">Kilo</option>
                                    <option value="piece">Piece</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="price" class="form-label">Price<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_price" placeholder="Enter price" required />
                            </div>

                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_quantity" placeholder="Enter quantity" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Category<span class="text-danger">*</span></label>
                                <select id="category_id2" class="form-select" required>
                                    <option value="">Select Category</option>
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>    


    @include('layout.scripts')

    <script>
        $(document).ready(function () {

            const store_id = localStorage.getItem('store_id')
                // Initialize DataTable
                $('#usersTable').DataTable({
                    ajax: {
                        url: '/api/admin/stores/'+store_id+'/products', // Your API endpoint for fetching users
                        type: 'GET',
                        headers: {
                                Authorization: `Bearer ${localStorage.getItem('token')}`, // Include token if required
                            },
                        dataSrc: '' // Adjust based on the response structure ('' if data is a direct array)
                    },
                    columns: [
                        { data: 'store.store_name' },
                        { 
                            data: 'image',
                            render: function(data, type, row) {
                                // Check if image exists and return the img tag, else show a default placeholder
                                const imageUrl = data ? '/storage/' + data : '/no-image.jpg';
                                return `<img src="${imageUrl}" alt="${row.store_name}" class="img-thumbnail" style="width: 50px; height: 50px;">`;
                            }
                        },
                        { data: 'name' },
                        { data: 'measurement' },
                        { data: 'price' },
                        { data: 'quantity' },
                        { data: 'category.name' },
                    ],
                    dom: 'lBfrtip', // Enable buttons for export functionality
                    buttons: [
                        'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                });
            });            
    </script>
    
</body>
</html>