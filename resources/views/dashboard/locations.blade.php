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
                            <h1 class="mb-0">Locations</h1>
                        </div>
                        <!-- Date range picker example-->
                        <div class="">
                            <button class="btn btn-primary shadow" data-bs-toggle="modal" data-bs-target="#locationModal">Create New Location</button>
                        </div>
                    </div>
                    <!-- Illustration dashboard card example-->
                    <div class="card mb-4 mt-5">
                        <div class="card-body p-5">
                            <div class="table-responsive">
                                <table class="table" id="locationsTable">
                                    <thead>
                                        <tr>
                                            <th>Province</th>
                                            <th>City</th>
                                            <th>Barangay</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Locations will be populated here -->
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

    <!-- Modal for Create Location -->
    <div class="modal fade" id="locationModal" tabindex="-1" aria-labelledby="locationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="locationModalLabel">Create New Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="locationForm">
                        <div class="mb-3">
                            <label for="createCity" class="form-label">City</label>
                            <select class="form-select" id="createCity" required>
                                <option value="">Select City</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="createBarangay" class="form-label">Barangay</label>
                            <select class="form-select" id="createBarangay" required>
                                <option value="">Select Barangay</option>
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

    <!-- Modal for Update Location -->
    <div class="modal fade" id="updateLocationModal" tabindex="-1" aria-labelledby="updateLocationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateLocationModalLabel">Update Location</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="updateLocationForm">
                        <input type="hidden" id="updateLocationId">
                        <div class="mb-3">
                            <label for="updateCity" class="form-label">City</label>
                            <select class="form-select" id="updateCity" required>
                                <option value="">Select City</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="updateBarangay" class="form-label">Barangay</label>
                            <select class="form-select" id="updateBarangay" required>
                                <option value="">Select Barangay</option>
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


    @include('layout.scripts')

    <script>
        $(document).ready(function () {
            // Initialize the DataTable
            const table = $('#locationsTable').DataTable({
                ajax: {
                    url: '/api/locations', // Replace with your API endpoint
                    type: 'GET',
                    dataSrc: '' // Since the response is an array of objects
                },
                columns: [
                    { data: 'province' },
                    { data: 'city' },
                    { data: 'barangay' },
                    {
                        data: 'id',
                        render: function (data, type, row) {
                            return `
                                <button class="btn btn-danger btn-sm" onclick="deleteLocation(${data})">Delete</button>
                            `;
                        }
                    }
                ]
            });
    
            // Refresh table after actions (optional)
            function refreshTable() {
                table.ajax.reload();
            }
    
            // Example delete function
            function deleteLocation(id) {
                if (confirm('Are you sure you want to delete this location?')) {
                    axios.delete(`/api/locations/${id}`)
                        .then(response => {
                            alert(response.data.message);
                            refreshTable();
                        })
                        .catch(error => {
                            console.error(error);
                            alert('Failed to delete location');
                        });
                }
            }
    
            // Attach the delete function globally for dynamic elements
            window.deleteLocation = deleteLocation;
        });
    </script>    

    //CREATE LOCATION
    <script>
        const provinceCode = "0308"; // Bataan Province Code

        // Load cities for the selected province (Bataan)
        function loadCities() {
            const cities = Philippines.getCityMunByProvince(provinceCode); // Fetch cities
            const citySelect = $('#createCity');
            citySelect.empty().append('<option value="">Select City</option>'); // Reset city options

            cities.forEach(city => {
                citySelect.append(`<option value="${city.mun_code}">${city.name}</option>`); // Populate city dropdown
            });
        }

        // Load barangays based on the selected city
        function loadBarangays(cityCode) {
            const barangays = Philippines.getBarangayByMun(cityCode); // Fetch barangays
            const barangaySelect = $('#createBarangay');
            barangaySelect.empty().append('<option value="">Select Barangay</option>'); // Reset barangay options

            barangays.forEach(barangay => {
                barangaySelect.append(`<option value="${barangay.mun_code}">${barangay.name}</option>`); // Populate barangay dropdown
            });
        }

        // Event: When a city is selected, load its barangays
        $('#createCity').on('change', function () {
            const cityCode = $(this).val();
            if (cityCode) {
                loadBarangays(cityCode);
            } else {
                $('#createBarangay').empty().append('<option value="">Select Barangay</option>'); // Reset barangay dropdown
            }
        });

        // Handle form submission for creating a new location
        $('#locationForm').on('submit', function (e) {
            e.preventDefault(); // Prevent default form submission

            const cityCode = $('#createCity').val();
            const cityName = $('#createCity option:selected').text(); // Get selected city name
            const barangayCode = $('#createBarangay').val();
            const barangayName = $('#createBarangay option:selected').text().toUpperCase();

            if (!cityCode || !barangayCode) {
                alert('Please select both City and Barangay.');
                return;
            }

            // Post new location data to the API
            axios.post('/api/locations', {
                province: 'BATAAN',
                city_code: cityCode,
                city: cityName,
                barangay_code: barangayCode,
                barangay: barangayName
            })
            .then(response => {
                alert('Location created successfully!');
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById("locationModal"));
                modal.hide();
                $('#locationForm')[0].reset(); // Reset form
                $('#locationsTable').DataTable().ajax.reload(); // Refresh table
            })
            .catch(error => {
                console.error(error);
                alert('Failed to create location.');
            });
        });


        // Initialize cities dropdown when the modal is shown
        $('#locationModal').on('shown.bs.modal', function () {
            loadCities();
        });
    </script>
    
</body>
</html>