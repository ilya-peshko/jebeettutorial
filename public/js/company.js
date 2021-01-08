class Company {
    constructor(companyId, pathToRedirect)
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
                    window.location.replace(pathToRedirect);
                })
            )
        }

        this.init = () => {
            console.log(pathToRedirect);
            let deleteButton = document.getElementById('delete-company');
            deleteButton.addEventListener('click', function (event) {
                console.log(companyId);
                deleteCompany().then();
            });
        }
    }
}