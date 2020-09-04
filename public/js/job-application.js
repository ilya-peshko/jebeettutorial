class JobApplication {
    constructor(userId)
    {
        this.init = () => {
            getJobApplications();
        };

        function getJobApplications(page = 1)
        {
            $(document).ready(function () {
                $.ajax({
                    url: '/api/user/'+userId+'/job-application/responses?page='+page,
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
                        $('#job-applications-list').html(data);
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


