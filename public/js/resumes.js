class Resumes {
    constructor(userId)
    {
        const getQueryParams = ( params, url ) => {
            let href = url;
            let reg = new RegExp('[?&]' + params + '=([^&#]*)', 'i');
            let queryString = reg.exec(href);
            return queryString ? queryString[1] : null;
        };

        this.init = () => {
            getResumeList();

            $(document).on('click', '#search', function () {
                getResumeList(1, document.getElementById("resume-search").value);
            })

            $(document).on('click', '.page-link', function (e) {
                e.preventDefault();
                if (!$(this).parent().hasClass('disabled')) {
                    let page = getQueryParams('page', $(this).attr('href'));
                    getResumeList(page, document.getElementById("resume-search").value);
                }
            })
        };

        function getResumeList(page = 1, searchQuery = '')
        {
            $(document).ready(function () {
                $.ajax({
                    url: '/api/user/'+userId+'/resume/list?page='+page+'&query='+searchQuery,
                    type: 'GET',
                    dataType: 'html',
                    async: true,

                    beforeSend: function () {
                        $('body').removeClass('loaded');
                    },
                    complete: function () {
                    },
                    success: function (data, status) {
                        $('#resumes').html(data);
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


