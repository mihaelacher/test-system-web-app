var test = {
    loadTests: function () {
        var testsTable = $('#testsIndexTable');
        if (testsTable.length) {
            testsTable.DataTable({
                ajax: '/ajax/tests/getTests',
                columns: [{
                    data: 'name',
                    name: 'name'
                }, {
                    data: 'intro_text',
                    name: 'intro_text'
                }, {
                    data: 'max_duration',
                    name: 'max_duration'
                }],
                responsive: true
            });
        }
    },
    init:function () {
        this.loadTests();
    }
};
test.init();
