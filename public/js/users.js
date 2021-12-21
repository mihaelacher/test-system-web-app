var user = {
    loadUsers: function () {
        var isSelectable = (window.location.pathname.indexOf('inviteUsers') !== -1);
        var usersTable = $('#usersIndexTable');
        if (usersTable.length) {
            usersTable.DataTable({
                ajax: '/ajax/users/getUsers',
                columns: [{
                    data: 'full_name',
                    name: 'full_name'
                }, {
                    data: 'username',
                    name: 'username'
                },{
                    data: 'email',
                    name: 'email'
                }, {
                    data: 'is_admin',
                    name: 'is_admin'
                }],
                responsive: true,
                select: isSelectable,
                bFilter: false,
                lengthChange: false,
                ordering: false,
                info: false,
            });
        }
    },
    handleUserSelectionOnSubmit: function () {
        var testForm = $('#testParticipationForm');

        testForm.on('submit', function () {
            var selectedIds = [];
            var selectedDataTableRows = $('#usersIndexTable').DataTable().rows({selected: true});

            if (selectedDataTableRows.count()) {
                selectedDataTableRows.data().each(function () {
                    $.each(this, function (index, value) {
                        selectedIds.push(value.id);
                    })
                });

                $('<input />').attr('type', 'hidden')
                    .attr('name', 'selected_user_ids')
                    .attr('value', selectedIds.join(','))
                    .appendTo(testForm);
            }
        });
    },
    init:function () {
        this.loadUsers();
        this.handleUserSelectionOnSubmit();
    }
};
user.init();
