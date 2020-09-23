class Facebook {
    constructor(appId, userFacebookId)
    {
        let name = document.getElementById('resume_name');
        let lastName = document.getElementById('resume_surname');
        let month = document.getElementById('resume_dateOfBirthday_month');
        let day = document.getElementById('resume_dateOfBirthday_day');
        let year = document.getElementById('resume_dateOfBirthday_year');
        let city = document.getElementById('resume_cityOfResidence');
        let gender = document.getElementById('resume_gender');

        window.fbAsyncInit = function () {
            FB.init({
                appId: appId,
                autoLogAppEvents: true,
                xfbml: true,
                version: 'v8.0'
            });

            FB.getLoginStatus(function (response) {
                if (response.status === 'connected' && userFacebookId === response.authResponse.userID) {
                    var accessToken = response.authResponse.accessToken;
                    sendData(accessToken, response.authResponse.userID);
                }
            });
        };

        function sendData(accessToken, userId)
        {
            return (
                fetch('/api/get-facebook-user', {
                    method: 'post',
                    headers: {
                        'Content-type': 'application/json',
                    },
                    body: JSON.stringify({
                        accessToken: accessToken,
                        userId: userId
                    }),
                }).then((response) => {
                    return response.json();
                }).then((result) => {
                    let dateArray = result.birthday.split('/');

                    name.value = result.first_name;
                    lastName.value = result.last_name;
                    month.value = dateArray[0];
                    day.value = dateArray[1];
                    year.value = dateArray[2];

                    if (result.hometown!== undefined) {
                        city.value = result.hometown;
                    }
                    if (result.gender!== undefined && result.gender === 'male') {
                        gender.value = 'Male';
                    } else {
                        gender.value = 'Female';
                    }
                })
            );
        }
    }
}
