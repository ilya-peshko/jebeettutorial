class ResumeControl {
    constructor(resumeId, pathToRedirect)
    {
        function deleteCompany()
        {
            return (
                fetch('/resume/edit/delete/' + resumeId, {
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
            let deleteButton = document.getElementById('delete-resume');
            deleteButton.addEventListener('click', function (event) {
                console.log(resumeId);
                deleteCompany().then();
            });
        }
    }
}