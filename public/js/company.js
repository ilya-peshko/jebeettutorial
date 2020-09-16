class Company {
    constructor(companyId)
    {
        function deleteCompany()
        {
            return (
                fetch('/company/edit/delete/' + companyId, {
                    method: 'post',
                    headers: {
                        'Content-type': 'application/json',
                    }
                }).then((response) => {
                    return response.json();
                }).then((result) => {
                    if (result.error) {
                        console.log(result.error);
                        throw result;
                    }
                    return result;
                })
            )
        }

        this.init = () => {
            let deleteButton = document.getElementById('delete-company');
            deleteButton.addEventListener('click', function (event) {
                console.log(companyId);
                deleteCompany().then();
            });
        }
    }
}