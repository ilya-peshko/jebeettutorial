class Jobs {
    constructor() {
        this.init = () => {
            $(document).ready(function () {
                $.ajax({
                    url: '/api/categories/activejobs',
                    type: 'GET',
                    dataType: 'html',
                    async: true,

                    success: function (data, status) {
                        $('#activejob-list').append(data);
                    },
                    error: function(xhr, status, error) {
                        let err = eval("(" + xhr.responseText + ")");
                        console.log(err);
                    }
                });
            });
        };
    }
}


