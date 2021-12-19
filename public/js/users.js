var user = {
    loadUsers: function () {
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
                responsive: true
            });
        }
    },
    init:function () {
        this.loadUsers();
    }
};
user.init();
