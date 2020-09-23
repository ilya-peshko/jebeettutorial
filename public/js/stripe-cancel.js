class StripeCancel{
    constructor(customerId, subscriptionId)
    {
        this.init = () => {
            let modal = document.querySelector(".modal-subscription");
            let modalText = document.getElementById('modal-text');
            let modalLoader = document.getElementById('modal-loader');
            let trigger = document.querySelector(".trigger-subscription");
            let closeButton = document.querySelector(".close-subscription-button");
            let form = document.getElementById('subscription-info-form');

            function cancelSubscription()
            {
                return fetch('/api/cancel-subscription', {
                    method: 'post',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        subscriptionId: subscriptionId,
                        customerId: customerId
                    }),
                })
                    .then(response => {
                        return response.json();
                    }).then((result) => {
                        if (result.error) {
                            throw result;
                        }
                        return result;
                    }).then(cancelSubscriptionResponse => {
                    })
                    .then(cancelInfo)
                    .catch((error) => {
                        modalText.innerHTML = error.error;
                        modalLoader.style.display = "none";
                        closeButton.style.visibility = 'visible';
                    });
            }

            function cancelInfo()
            {
                modalText.innerHTML = trans['cancel.succeeded']+'!';
                modalLoader.style.display = "none";
            }

            function toggleModal()
            {
                modal.classList.toggle("show-modal");
            }

            trigger.addEventListener("click", toggleModal);
            closeButton.addEventListener("click", toggleModal);
            form.addEventListener('submit', cancelSubscription);
        }
    }
}
