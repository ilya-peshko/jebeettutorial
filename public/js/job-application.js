class JobApplication {
    constructor(userId, locale)
    {
        const getQueryParams = ( params, url ) => {
            let href = url;
            let reg = new RegExp('[?&]' + params + '=([^&#]*)', 'i');
            let queryString = reg.exec(href);
            return queryString ? queryString[1] : null;
        };

        this.init = () => {
            getJobApplications();

            $(document).on('click', '.page-link', function (e) {
                e.preventDefault();
                if (!$(this).parent().hasClass('disabled')) {
                    let page = getQueryParams('page', $(this).attr('href'));
                    getJobApplications(page);
                }
            })
        };

        function getJobApplications(page = 1)
        {
            $(document).ready(function () {
                $.ajax({
                    url: '/'+locale+'/api/user/'+userId+'/job-application/responses?page='+page,
                    type: 'GET',
                    dataType: 'html',
                    async: true,

                    beforeSend: function () {
                        $('body').removeClass('loaded');
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


