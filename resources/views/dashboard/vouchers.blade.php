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
                        <!-- Date range picker example-->
                        <div class="">
                            <button class="btn btn-primary shadow" data-bs-toggle="modal" data-bs-target="#dataModal">Create New Voucher</button>
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
                                            <th>Actions</th>
                                            <th></th>
                                            <th></th>
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

    <!-- Modal for Create  -->
    <div class="modal fade" id="dataModal" tabindex="-1" aria-labelledby="dataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="dataModalLabel">Create New Voucher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="dataForm">
                        <div class="mb-3">
                            <label class="form-label">Code<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="code" placeholder="Enter code" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="description" placeholder="Enter description" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Min. Order Price<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="min_order_price" placeholder="Enter min. order price" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Value<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="value" placeholder="Enter value" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Is Percentage?<span class="text-danger">*</span></label>
                            <select class="form-select" id="is_percent" required>
                                <option value="true">Yes</option>
                                <option value="false">No</option>
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

    <!-- Modal for Update  -->
    <div class="modal fade" id="updateDataModal" tabindex="-1" aria-labelledby="updateDataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateDataModalLabel">Update Voucher</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateDataForm">
                        <input type="hidden" id="updateId">
                        <div class="mb-3">
                            <label class="form-label">Code<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editcode" placeholder="Enter code" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description<span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="editdescription" placeholder="Enter description" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Min. Order Price<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="editmin_order_price" placeholder="Enter min. order price" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Value<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="editvalue" placeholder="Enter value" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Is Percentage?<span class="text-danger">*</span></label>
                            <select class="form-select" id="editis_percent" required>
                                <option value="true">Yes</option>
                                <option value="false">No</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal to give voucher  -->
    <div class="modal fade" id="giveDataModal" tabindex="-1" aria-labelledby="giveDataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="giveDataModalLabel">Give Voucher to Customers</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="giveDataForm">
                        <input type="hidden" id="dataId">
                        <div class="mb-3">
                            <label class="form-label">Expiration Date<span class="text-danger">*</span></label>
                            <input type="date" class="form-control" id="expiration_date" placeholder="Select expiration date" required />
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Quantity<span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="amount" placeholder="Enter quantity" required />
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('layout.scripts')

    <script>
        $(document).ready(function () {
            // Initialize the DataTable
            const token = localStorage.getItem('token'); // Retrieve token from local storage

            const table = $('#table').DataTable({
                ajax: {
                    url: '/api/vouchers', // Replace with your API endpoint
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
                    {
                        data: 'id',
                        render: function (data, type, row) {
                            return `
                                <button class="btn btn-warning btn-sm" onclick="editData(${data})">Edit</button>
                            `;
                        }
                    },
                    {
                        data: 'id',
                        render: function (data, type, row) {
                            return `
                                <button class="btn btn-danger btn-sm" onclick="deleteData(${data})">Delete</button>
                            `;
                        }
                    },
                    {
                        data: 'id',
                        render: function (data, type, row) {
                            return `
                                <button class="btn btn-success btn-sm" onclick="giveData(${data})">Give to Customers</button>
                            `;
                        }
                    },
                ]
            });
    
            // Refresh table after actions (optional)
            function refreshTable() {
                table.ajax.reload();
            }
    
            // Example delete function
            function deleteData(id) {
                if (confirm('Are you sure you want to delete this voucher?')) {
                    axios.delete(`/api/vouchers/${id}`, { headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` } })
                        .then(response => {
                            alert(response.data.message);
                            refreshTable();
                        })
                        .catch(error => {
                            console.error(error);
                            alert('Failed to delete');
                        });
                }
            }
    
            // Attach the delete function globally for dynamic elements
            window.deleteData = deleteData;

            // Function to populate the update form with  data
            function editData(id) {
                axios.get(`/api/vouchers/${id}`, { headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` } })
                    .then(response => {
                        const i = response.data.voucher;
                        $('#updateId').val(i.id);
                        $('#editcode').val(i.code);
                        $('#editmin_order_price').val(i.min_order_price);
                        $('#editvalue').val(i.value);
                        $('#editdescription').val(i.description);
                        $('#editis_percent').val(i.is_percent.toString());

                        // Show the update modal
                        const updateModal = new bootstrap.Modal(document.getElementById('updateDataModal'));
                        updateModal.show();
                    })
                    .catch(error => {
                        console.error(error);
                        alert('Failed to load  details.');
                    });
            }

            // Handle update form submission
            $('#updateDataForm').on('submit', function (e) {
                e.preventDefault();

                const id = $('#updateId').val();
                const code = $('#editcode').val();
                const min_order_price = $('#editmin_order_price').val();
                const value = $('#editvalue').val();
                const description = $('#editdescription').val();
                const is_percent = $('#editis_percent').val() === 'true';

                axios.put(`/api/vouchers/${id}`, {
                    code: code,
                    min_order_price: min_order_price,
                    value: value,
                    description: description,
                    is_percent: is_percent,
                },{
                headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
                })
                .then(response => {
                    alert('Voucher updated successfully!');
                    
                    // Close modal
                    const updateModal = bootstrap.Modal.getInstance(document.getElementById("updateDataModal"));
                    updateModal.hide();

                    $('#table').DataTable().ajax.reload(); // Refresh table
                })
                .catch(error => {
                    console.error(error);
                    alert('Failed to update.');
                });
            });

            // Attach edit function globally for dynamic elements
            window.editData = editData;
            window.giveData = giveData;

            function giveData(id) {
                $('#dataId').val(id);
                const giveModal = new bootstrap.Modal(document.getElementById('giveDataModal'));
                giveModal.show();
            }

            $('#giveDataForm').on('submit', function (e) {
                e.preventDefault(); // Prevent default form submission

                const expiration_date = $('#expiration_date').val();
                const amount = $('#amount').val();
                const dataId = $('#dataId').val();

                // Post new  data to the API
                axios.post(`/api/vouchers/${dataId}/give-all`, {
                    expiration_date: expiration_date,
                    amount: amount,
                },{
                headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
                })
                .then(response => {
                    alert('The voucher has been successfully given to the customers!');
                    // Close modal
                    const modal = bootstrap.Modal.getInstance(document.getElementById("giveDataModal"));
                    modal.hide();
                    $('#giveDataForm')[0].reset(); // Reset form
                    $('#table').DataTable().ajax.reload(); // Refresh table
                })
                .catch(error => {
                    console.error(error);
                    alert('Failed to create.');
                });
            });
        });
    </script>    

    //CREATE 
    <script>

        // Handle form submission for creating a new 
        $('#dataForm').on('submit', function (e) {
            e.preventDefault(); // Prevent default form submission

            const code = $('#code').val();
            const min_order_price = $('#min_order_price').val();
            const value = $('#value').val();
            const description = $('#description').val();
            const is_percent = $('#is_percent').val() === 'true';

            // Post new  data to the API
            axios.post('/api/vouchers', {
                code: code,
                min_order_price: min_order_price,
                value: value,
                description: description,
                is_percent: is_percent,
            },{
            headers: { 'Authorization': `Bearer ${localStorage.getItem('token')}` }
            })
            .then(response => {
                alert('Voucher created successfully!');
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById("dataModal"));
                modal.hide();
                $('#dataForm')[0].reset(); // Reset form
                $('#table').DataTable().ajax.reload(); // Refresh table
            })
            .catch(error => {
                console.error(error);
                alert('Failed to create.');
            });
        });
    </script>
    
</body>
</html>