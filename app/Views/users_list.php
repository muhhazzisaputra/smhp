<!-- In your view file (e.g., app/Views/users_list.php) -->
<!DOCTYPE html>
<html>
<head>
    <title>DataTables Server-side CI4</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
</head>
<body>

<h2>Users List</h2>

<!-- Custom filter inputs -->
<input type="text" id="username_filter" placeholder="Search by Username">
<select id="status_filter">
    <option value="">Status</option>
    <!-- Roles will be populated dynamically -->
    <option value="active">Active</option>
    <option value="inactive">Inactive</option>
</select>
<button id="searchBtn">Search</button>

<table id="usersTable" class="display">
    <thead>
        <tr>
            <th>#</th>
            <th>Option</th>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Status</th>
        </tr>
    </thead>
</table>

<!-- Edit Modal -->
<div id="editModal" style="display:none;">
    <form id="editForm">
        <input type="hidden" name="id" id="edit_id">
        <label>Name:</label>
        <input type="text" name="name" id="edit_name">
        <label>Email:</label>
        <input type="email" name="email" id="edit_email">
        <label>Status:</label>
        <select name="status" id="edit_status">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
        <button type="submit">Save</button>
        <button type="button" id="closeModal">Cancel</button>
    </form>
</div>


<script>
$(document).ready(function () {
    let table = $('#usersTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "<?= site_url('users/datatables') ?>",
            type: "POST",
            data: function (d) {
                d.username = $('#username_filter').val(); // custom filter
                d.status = $('#status_filter').val();
            }
        },
        columns: [
            {
                data: null,
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: null,
                render: function (data, type, row) {
                    return `
                        <button class="editBtn" data-id="${row.id}" data-name="${row.name}" data-email="${row.email}" data-status="${row.status}">Edit</button>
                        <button class="deleteBtn" data-id="${row.id}">Delete</button>
                    `;
                }
            },
            { data: 'id' },
            { data: 'name' },
            { data: 'email' },
            { data: 'status' }
        ]
    });

    // Trigger the custom filter on button click
    $('#searchBtn').on('click', function () {
        table.ajax.reload();
    });
});

// Open modal with user data
$('#usersTable').on('click', '.editBtn', function () {
    $('#edit_id').val($(this).data('id'));
    $('#edit_name').val($(this).data('name'));
    $('#edit_email').val($(this).data('email'));
    $('#edit_status').val($(this).data('status'));
    $('#editModal').show();
});

// Close modal
$('#closeModal').on('click', function () {
    $('#editModal').hide();
});

// Submit update via AJAX
$('#editForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: '<?= site_url('users/update') ?>',
        type: 'POST',
        data: $(this).serialize(),
        success: function (response) {
            alert('User updated!');
            $('#editModal').hide();
            $('#usersTable').DataTable().ajax.reload();
        }
    });
});

// Delete user via AJAX
$('#usersTable').on('click', '.deleteBtn', function () {
    if (confirm('Are you sure?')) {
        const userId = $(this).data('id');
        $.post('<?= site_url('users/delete') ?>', { id: userId }, function (response) {
            alert('User deleted!');
            $('#usersTable').DataTable().ajax.reload();
        });
    }
});

</script>

</body>
</html>
