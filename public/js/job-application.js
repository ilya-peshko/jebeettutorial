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
                        $(document).ready(function () {
                            $('body').addClass('loaded_hiding');
                            setTimeout(function () {
                                $('body').addClass('loaded').removeClass('loaded_hiding');
                                document.body.classList.remove('loaded_hiding');
                            }, 500);
                        });
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


