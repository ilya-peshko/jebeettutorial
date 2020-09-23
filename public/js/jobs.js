class Jobs {
    constructor(locale)
    {
        this.init = () => {
            getActiveJobs();
        };
        function getActiveJobs(page = 1)
        {
            $(document).ready(function () {
                $.ajax({
                    url: '/'+ locale +'/api/categories/activejobs',
                    type: 'GET',
                    dataType: 'html',
                    async: true,

                    beforeSend: function () {
                    },
                    complete: function () {
                    },
                    success: function (data, status) {
                        $('#activejob-list').append(data);
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


