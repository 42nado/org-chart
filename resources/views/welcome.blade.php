<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organizational Chart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h2>Organizational Chart</h2>

        <!-- Form to Add/Update Position -->
        <form id="positionForm">
            <div class="form-group">
                <label for="name">Position Name:</label>
                <input type="text" class="form-control" name="name" id="name"
                    placeholder="Enter position name" required>
            </div>
            <div class="form-group">
                <label for="report_to">Reports To:</label>
                <select class="form-control" name="report_to" id="report_to">
                    <option value="">Select a position</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <!-- Search and Sort Controls -->
        <div class="mt-5">
            <input type="text" class="form-control" id="searchInput" placeholder="Search by position name">
            <select class="form-control mt-2" id="sortSelect">
                <option value="">Sort by</option>
                <option value="name-asc">Name (A-Z)</option>
                <option value="name-desc">Name (Z-A)</option>
            </select>
        </div>
        <!-- Table to Display Positions -->
        <h3 class="mt-5">Positions</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Position Name</th>
                    <th>Reports To</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="positionsTable">
            </tbody>
        </table>
    </div>

    <script>
        const apiUrl = '/api/positions';

        // Fetch positions and populate table and select options
        function fetchPositions(query = '', sortBy = '') {
            axios.get(apiUrl, {
                    params: {
                        search: query,
                        sort: sortBy
                    }
                })
                .then(response => {
                    const positions = response.data;
                    let tableRows = '';
                    let selectOptions = '<option value="">Select a position</option>';

                    positions.forEach(position => {
                        tableRows += `
                            <tr>
                                <td>${position.id}</td>
                                <td>${position.name}</td>
                                <td>${position.parent_position ? position.parent_position.name : ''}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm" onclick="editPosition(${position.id})">Edit</button>
                                    <button class="btn btn-danger btn-sm" onclick="deletePosition(${position.id})">Delete</button>
                                </td>
                            </tr>
                        `;

                        selectOptions += `<option value="${position.id}">${position.name}</option>`;
                    });

                    document.getElementById('positionsTable').innerHTML = tableRows;
                    document.getElementById('report_to').innerHTML = selectOptions;
                })
                .catch(error => console.error('Error fetching positions:', error));
        }
        // Add or Update Position
        document.getElementById('positionForm').addEventListener('submit', function(event) {
            event.preventDefault();

            const name = document.getElementById('name').value;
            const reportTo = document.getElementById('report_to').value || null;

            axios.post(apiUrl, {
                    name: name,
                    report_to: reportTo
                })
                .then(response => {
                    fetchPositions();
                    document.getElementById('positionForm').reset();
                })
                .catch(error => console.error('Error adding/updating position:', error));
        });

        // Edit Position
        function editPosition(id) {
            axios.get(`${apiUrl}/${id}`)
                .then(response => {
                    const position = response.data;
                    document.getElementById('name').value = position.name;
                    document.getElementById('report_to').value = position.report_to || '';
                })
                .catch(error => console.error('Error fetching position details:', error));
        }

        // Delete Position
        function deletePosition(id) {
            if (confirm('Are you sure you want to delete this position?')) {
                axios.delete(`${apiUrl}/${id}`)
                    .then(response => {
                        fetchPositions();
                    })
                    .catch(error => console.error('Error deleting position:', error));
            }
        }

        // Handle Search Input
        document.getElementById('searchInput').addEventListener('input', function() {
            const query = this.value;
            const sortBy = document.getElementById('sortSelect').value;
            fetchPositions(query, sortBy);
        });

        // Handle Sort Select
        document.getElementById('sortSelect').addEventListener('change', function() {
            const sortBy = this.value;
            const query = document.getElementById('searchInput').value;
            fetchPositions(query, sortBy);
        });

        // Initial Fetch of Positions
        fetchPositions();
    </script>
</body>

</html>
