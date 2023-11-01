$(document).ready(function () {
    loadUsers();

    $('#add-user').click(function (e) {
        e.preventDefault();
        var name = $('#name').val();
        var surname = $('#surname').val();
        var position = $('#position').val();

        $.ajax({
            type: 'POST',
            url: 'api.php',
            data: {
                action: 'add',
                name: name,
                surname: surname,
                position: position
            },
            success: function () {
                loadUsers();
                $('#name, #surname').val('');
            }
        });
    });

    $('#user-list').on('click', '.edit-user', function() {
        var userId = $(this).data('id');
        openEditModal(userId);
    });

    $('#user-list').on('click', '.delete-user', function() {
        var userId = $(this).data('id');
        if (confirm('Вы уверены, что хотите удалить пользователя?')) {
            deleteUser(userId);
        }
    });

    function loadUsers() {
        $.ajax({
            type: 'POST',
            url: 'api.php',
            data: { action: 'get' },
            success: function (data) {
                $('#user-list').html(data);
            }
        });
    }

    function deleteUser(userId) {
        $.ajax({
            type: 'POST',
            url: 'api.php',
            data: {
                action: 'delete',
                userId: userId
            },
            success: function () {
                loadUsers();
            }
        });
    }

    $('#save-edit-user').click(function () {
        var userId = $('#edit-user-id').val();
        var newName = $('#edit-name').val();
        var newSurname = $('#edit-surname').val();
        var newPosition = $('#edit-position').val();

        $.ajax({
            type: 'POST',
            url: 'api.php',
            data: {
                action: 'edit',
                userId: userId,
                name: newName,
                surname: newSurname,
                position: newPosition
            },
            success: function () {
                loadUsers();
            }
        });
    });

    function openEditModal(userId) {
        $.ajax({
            type: 'POST',
            url: 'api.php',
            data: { action: 'getUser', userId: userId },
            success: function (data) {
                var user = JSON.parse(data);
                $('#edit-user-id').val(user.id);
                $('#edit-name').val(user.name);
                $('#edit-surname').val(user.surname);
                $('#edit-position').val(user.position);

                $('#edit-modal').show();
            }
        });
    }

    $('#close-edit-modal').click(function () {
        $('#edit-modal').hide();
    });

    $('#execute-query').click(function () {
        $('#result-list').empty();

        $.ajax({
            type: 'POST',
            url: 'api.php',
            data: { action: 'execute_query' },
            success: function (data) {
                var results = JSON.parse(data);
                var tableBody = $('#result-list');
                for (var i = 0; i < results.length; i++) {
                    var result = results[i];
                    var row = '<tr>';
                    row += '<td>' + result.product_name + '</td>';
                    row += '<td>' + result.field1_name + '</td>';
                    row += '<td>' + result.field1_value + '</td>';
                    row += '<td>' + result.field2_name + '</td>';
                    row += '<td>' + result.field2_value + '</td>';
                    row += '</tr>';
                    tableBody.append(row);
                }
            }
        });
    });

});
