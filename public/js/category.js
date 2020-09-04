class Category {
    constructor(slug)
    {
        const getQueryParams = ( params, url ) => {
            let href = url;
            let reg = new RegExp('[?&]' + params + '=([^&#]*)', 'i');
            let queryString = reg.exec(href);
            return queryString ? queryString[1] : null;
        };

        this.init = () => {
            getActiveJobsByCategory();
            $(document).on('click', '#search', function () {
                getActiveJobsByCategory(1, document.getElementById("search-input").value);
            })

            $(document).on('click', '.page-link', function (e) {
                e.preventDefault();
                if (!$(this).parent().hasClass('disabled')) {
                    let page = getQueryParams('page', $(this).attr('href'));
                    getActiveJobsByCategory(page, document.getElementById("search-input").value);
                }
            })
        };

        function getActiveJobsByCategory(page = 1, searchQuery = '')
        {
            $(document).ready(function () {
                $.ajax({
                    url: '/api/category/'+slug+'?page='+page+'&query='+searchQuery,
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
                        $('#active-jobs-by-category').html(data);
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


