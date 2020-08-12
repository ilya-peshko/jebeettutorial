class Resumes {
    constructor(userId) {
        const getQueryParams = ( params, url ) => {
            let href = url;
            let reg = new RegExp( '[?&]' + params + '=([^&#]*)', 'i' );
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
                if(!$(this).parent().hasClass('disabled')) {
                    let page = getQueryParams('page', $(this).attr('href'));
                    getResumeList(page, document.getElementById("resume-search").value);
                }
            })
        };

        function getResumeList(page = 1, searchQuery = '') {
            $(document).ready(function () {
                console.log(searchQuery);
                $.ajax({
                    url: '/api/user/'+userId+'/resume/list?page='+page+'&query='+searchQuery,
                    type: 'GET',
                    dataType: 'html',
                    async: true,

                    beforeSend: function(){
                        $('.loader').show()
                    },
                    complete: function(){
                        $('.loader').hide();
                    },
                    success: function (data, status) {
                        $('#resumes').html(data);
                    },
                    error: function(xhr, status, error) {
                        let err = eval("(" + xhr.responseText + ")");
                        console.log(err);
                    }
                });
            });
        }
    }
}


