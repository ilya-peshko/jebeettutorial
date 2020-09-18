class FacebookPost {
    constructor(jobId)
    {
        this.init = () => {
            function postJobOnFacebook()
            {
                return (
                    fetch('/job/'+ jobId +'/edit/post/facebook', {
                        method: 'post',
                        headers: {
                            'Content-type': 'application/json',
                        }
                    }).then((response) => {
                        return response.json();
                    }).then((result) => {
                        console.log(result);
                        if (result.e) {
                            console.log(result.e);
                            throw result;
                        }
                        return result;
                    })
                )
            }
            let facebookButton = document.getElementById('facebook-post');
            facebookButton.addEventListener('click', function (event) {
                postJobOnFacebook().then();
            });

        }
    }
}