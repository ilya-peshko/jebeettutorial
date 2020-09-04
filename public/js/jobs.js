class Jobs {
    constructor()
    {
        this.init = () => {
            getActiveJobs();
        };
        function getActiveJobs(page = 1)
        {
            $(document).ready(function () {
                $.ajax({
                    url: '/api/categories/activejobs',
                    type: 'GET',
                    dataType: 'html',
                    async: true,

                    beforeSend: function () {
                        $('.loader').show()
                    },
                    complete: function () {
                        $('.loader').hide();
                    },
                    success: function (data, status) {
                        $('#activejob-list').append(data);
                    },
                    error: function (xhr, status, error) {
                        let err = eval("(" + xhr.responseText + ")");
                        console.log(err);
                    }
                });
            });
        }
    }
}


