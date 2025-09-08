
$(document).ready(function () {

    // =====================
    // HELPER FUNCTIONS
    // =====================
    function getSelectedFeatures() {
        let features = [];
        $('#roomFeatures input:checked').each(function () {
            features.push($(this).val());
        });
        return features;
    }

    function resetAddRoomForm() {
        $('#addRoomModal input, #addRoomModal textarea').val('');
        $('#roomFeatures input').prop('checked', false);
        $('#roomImages').val('');
    }

    function resetEditRoomForm() {
        $('#editRoomModal input, #editRoomModal textarea').val('');
        $('#editRoomFeatures input').prop('checked', false);
        $('#editRoomImages').val('');
        $('#existingImages').html('');
    }

    // =====================
    // FETCH ALL ROOMS
    // =====================
    function fetchRooms() {
        $.get('rooms_crud.php', { action: 'read' }, function (res) {
            console.log('Fetch Rooms:', res);
            if (!Array.isArray(res)) {
                alert('Invalid response from server.');
                return;
            }
            let rows = '';
            res.forEach(room => {
                let features = JSON.parse(room.features || '[]').join(', ');
                let images = JSON.parse(room.images || '[]')
                    .map(img => `<img src="uploads/${img}" width="50">`).join(' ');

                rows += `
                  <tr>
                    <td>${room.id}</td>
                    <td>${room.name}</td>
                    <td>${room.type}</td>
                    <td>${room.price}</td>
                    <td>${room.capacity}</td>
                    <td>${room.status}</td>
                    <td>${room.description}</td>
                    <td>${features}</td>
                    <td>${images}</td>
                    <td>
                      <button class="btn btn-info btn-sm view-room" data-id="${room.id}">View</button>
                      <button class="btn btn-warning btn-sm edit-room" data-id="${room.id}">Edit</button>
                      <button class="btn btn-danger btn-sm delete-room" data-id="${room.id}">Delete</button>
                    </td>
                  </tr>
                `;
            });
            $('#roomsTable tbody').html(rows);
        }, 'json').fail(function (xhr, status, error) {
            console.error('Fetch Rooms error:', error, xhr.responseText);
        });
    }

    // =====================
    // ADD ROOM
    // =====================
    $('#addRoomModal .btn-primary').on('click', function () {
        let formData = new FormData();
        formData.append('action', 'create');
        formData.append('name', $('#roomName').val());
        formData.append('type', $('#roomType').val());
        formData.append('price', $('#roomPrice').val());
        formData.append('capacity', $('#roomCapacity').val());
        formData.append('status', $('#roomStatus').val());
        formData.append('description', $('#roomDescription').val());
        formData.append('features', JSON.stringify(getSelectedFeatures()));

        let files = $('#roomImages')[0].files;
        for (let i = 0; i < files.length; i++) {
            formData.append('images[]', files[i]);
        }

        $.ajax({
            url: 'rooms_crud.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (res) {
                console.log('Add Room response:', res);
                if (res.success) {
                    $('#addRoomModal').modal('hide');
                    resetAddRoomForm();
                    fetchRooms();
                } else {
                    alert(res.message || 'Failed to add room.');
                }
            },
            error: function (xhr, status, error) {
                console.error('Add Room error:', error, xhr.responseText);
            }
        });
    });

    // =====================
    // VIEW ROOM
    // =====================
    $(document).on('click', '.view-room', function () {
        let id = $(this).data('id');
        $.get('rooms_crud.php', { action: 'readOne', id }, function (res) {
            console.log('View Room:', res);
            if (res.error) {
                alert(res.error);
                return;
            }
            $('#viewRoomModal #viewRoomName').text(res.name);
            $('#viewRoomModal #viewRoomType').text(res.type);
            $('#viewRoomModal #viewRoomPrice').text(res.price);
            $('#viewRoomModal #viewRoomCapacity').text(res.capacity);
            $('#viewRoomModal #viewRoomStatus').text(res.status);
            $('#viewRoomModal #viewRoomDescription').text(res.description);
            $('#viewRoomModal #viewRoomFeatures').text(JSON.parse(res.features || '[]').join(', '));
            $('#viewRoomModal #viewRoomImages').html(
                JSON.parse(res.images || '[]')
                    .map(img => `<img src="uploads/${img}" width="80">`).join(' ')
            );
            $('#viewRoomModal').modal('show');
        }, 'json');
    });

    // =====================
    // EDIT ROOM (GET DATA)
    // =====================
    $(document).on('click', '.edit-room', function () {
        let id = $(this).data('id');
        $.get('rooms_crud.php', { action: 'readOne', id }, function (res) {
            console.log('Edit Room fetch:', res);
            if (res.error) {
                alert(res.error);
                return;
            }
            $('#editRoomId').val(res.id);
            $('#editRoomName').val(res.name);
            $('#editRoomType').val(res.type);
            $('#editRoomPrice').val(res.price);
            $('#editRoomCapacity').val(res.capacity);
            $('#editRoomStatus').val(res.status);
            $('#editRoomDescription').val(res.description);

            let features = JSON.parse(res.features || '[]');
            $('#editRoomFeatures input').each(function () {
                $(this).prop('checked', features.includes($(this).val()));
            });

            $('#existingImages').html(
                JSON.parse(res.images || '[]')
                    .map(img => `<img src="uploads/${img}" width="80">`).join(' ')
            );

            $('#editRoomModal').modal('show');
        }, 'json');
    });

    // =====================
    // UPDATE ROOM
    // =====================
    $('#editRoomModal .btn-primary').on('click', function () {
        let formData = new FormData();
        formData.append('action', 'update');
        formData.append('id', $('#editRoomId').val());
        formData.append('name', $('#editRoomName').val());
        formData.append('type', $('#editRoomType').val());
        formData.append('price', $('#editRoomPrice').val());
        formData.append('capacity', $('#editRoomCapacity').val());
        formData.append('status', $('#editRoomStatus').val());
        formData.append('description', $('#editRoomDescription').val());
        formData.append('features', JSON.stringify(
            $('#editRoomFeatures input:checked').map(function () {
                return $(this).val();
            }).get()
        ));

        let files = $('#editRoomImages')[0].files;
        for (let i = 0; i < files.length; i++) {
            formData.append('images[]', files[i]);
        }

        $.ajax({
            url: 'rooms_crud.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (res) {
                console.log('Update Room response:', res);
                if (res.success) {
                    $('#editRoomModal').modal('hide');
                    resetEditRoomForm();
                    fetchRooms();
                } else {
                    alert(res.message || 'Failed to update room.');
                }
            },
            error: function (xhr, status, error) {
                console.error('Update Room error:', error, xhr.responseText);
            }
        });
    });

    // =====================
    // DELETE ROOM
    // =====================
    $(document).on('click', '.delete-room', function () {
        if (!confirm('Are you sure you want to delete this room?')) return;
        let id = $(this).data('id');
        $.post('rooms_crud.php', { action: 'delete', id }, function (res) {
            console.log('Delete Room response:', res);
            if (res.success) {
                fetchRooms();
            } else {
                alert(res.message || 'Failed to delete room.');
            }
        }, 'json');
    });

    // =====================
    // INITIAL LOAD
    // =====================
    fetchRooms();
});

