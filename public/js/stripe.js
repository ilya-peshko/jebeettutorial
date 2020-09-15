class StripeSubscribe {
    constructor(pk_key, customerId)
    {
        this.init = () => {
            let modal = document.querySelector(".modal-subscription");
            let modalText = document.getElementById('modal-text');
            let modalTextDefault = modalText.textContent;
            let modalLoader = document.getElementById('modal-loader');
            let trigger = document.querySelector(".trigger-subscription");
            let closeButton = document.querySelector(".close-subscription-button");
            let stripe = Stripe(pk_key);
            let elements = stripe.elements();
            let form = document.getElementById('subscription-form');
            let style = {
                base: {
                    color: "#32325d",
                    fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                    fontSmoothing: "antialiased",
                    fontSize: "16px",
                    "::placeholder": {
                        color: "#aab7c4"
                    }
                },
                invalid: {
                    color: "#fa755a",
                    iconColor: "#fa755a"
                }
            };
            let card = elements.create("card", {style: style});

            card.mount("#card-element");
            card.on('change', showCardError);

            function createPaymentMethod({ card, isPaymentRetry, invoiceId })
            {
                let billingName = document.querySelector('#name').value;
                stripe
                    .createPaymentMethod({
                        type: 'card',
                        card: card,
                        billing_details: {
                            name: billingName,
                        },
                    })
                    .then((result) => {
                        if (result.error) {
                            showCardError(result);
                            showModalError(result);
                        } else {
                            if (isPaymentRetry) {
                                // Update the payment method and retry invoice payment
                                retryInvoiceWithNewPaymentMethod({
                                    customerId: customerId,
                                    paymentMethodId: result.paymentMethod.id,
                                    invoiceId: invoiceId,
                                    priceId: document.querySelector('input[name="radio"]:checked').dataset.priceId,
                                });
                            } else {
                                // Create the subscription
                                createSubscription({
                                    customerId: customerId,
                                    paymentMethodId: result.paymentMethod.id,
                                    priceId: document.querySelector('input[name="radio"]:checked').dataset.priceId,
                                });
                            }
                        }
                    });
            }

            function createSubscription({ customerId, paymentMethodId, priceId })
            {
                return (
                    fetch('/api/create-subscription', {
                        method: 'post',
                        headers: {
                            'Content-type': 'application/json',
                        },
                        body: JSON.stringify({
                            customerId: customerId,
                            paymentMethodId: paymentMethodId,
                            priceId: priceId,
                        }),
                    }).then((response) => {
                        return response.json();
                    })
                        // If the card is declined, display an error to the user.
                        .then((result) => {
                            if (result.error) {
                                // The card had an error when trying to attach it to a customer.
                                throw result;
                            }
                            return result;
                        })
                        // Normalize the result to contain the object returned by Stripe.
                        // Add the additional details we need.
                        .then((result) => {
                            return {
                                paymentMethodId: paymentMethodId,
                                priceId: document.querySelector('input[name="radio"]:checked').dataset.priceId,
                                subscription: result,
                            };
                        })
                        // Some payment methods require a customer to be on session
                        // to complete the payment process. Check the status of the
                        // payment intent to handle these actions.
                        .then(handlePaymentThatRequiresCustomerAction)
                        // If attaching this card to a Customer object succeeds,
                        // but attempts to charge the customer fail, you
                        // get a requires_payment_method error.
                        .then(handleRequiresPaymentMethod)
                        // No more actions required. Provision your service for the user.
                        .then(onSubscriptionComplete)
                        .catch((error) => {
                            showModalError(error);
                            showCardError(error);
                        })
                );
            }

            function handlePaymentThatRequiresCustomerAction({
                subscription,
                invoice,
                priceId,
                paymentMethodId,
                isRetry,
            })
            {
                if (subscription && subscription.status === 'active') {
                    // Subscription is active, no customer actions required.
                    return { subscription, priceId, paymentMethodId };
                }
                let paymentIntent = invoice ? invoice.payment_intent : subscription.latest_invoice.payment_intent;

                if (
                    paymentIntent.status === 'requires_action' ||
                    (isRetry === true && paymentIntent.status === 'requires_payment_method')
                ) {
                    return stripe
                        .confirmCardPayment(paymentIntent.client_secret, {
                            payment_method: paymentMethodId,
                        })
                        .then((result) => {
                            if (result.error) {
                                // Start code flow to handle updating the payment details.
                                // Display error message in your UI.
                                // The card was declined (i.e. insufficient funds, card has expired, etc).
                                throw result;
                            } else {
                                if (result.paymentIntent.status === 'succeeded') {
                                    // Show a success message to your customer.
                                    // There's a risk of the customer closing the window before the callback.
                                    // We recommend setting up webhook endpoints later in this guide.
                                    modalText.innerHTML = "Payment status succeeded!";
                                    return {
                                        priceId: document.querySelector('input[name="radio"]:checked').dataset.priceId,
                                        subscription: subscription,
                                        invoice: invoice,
                                        paymentMethodId: paymentMethodId,
                                    };
                                }
                            }
                        }).catch((error) => {
                            showCardError(error);
                            showModalError(error);
                        });
                } else {
                    // No customer action needed.
                    return { subscription, priceId, paymentMethodId };
                }
            }

            function handleRequiresPaymentMethod({
                subscription,
                paymentMethodId,
                priceId,
            })
            {
                if (subscription.status === 'active') {
                    // subscription is active, no customer actions required.
                    return { subscription, priceId, paymentMethodId };
                } else if (
                    subscription.latest_invoice.payment_intent.status ===
                    'requires_payment_method'
                ) {
                    // Using localStorage to manage the state of the retry here,
                    // feel free to replace with what you prefer.
                    // Store the latest invoice ID and status.
                    localStorage.setItem('latestInvoiceId', subscription.latest_invoice.id);
                    localStorage.setItem(
                        'latestInvoicePaymentIntentStatus',
                        subscription.latest_invoice.payment_intent.status
                    );
                    throw { error: { message: 'Your card was declined.' } };
                } else {
                    return { subscription, priceId, paymentMethodId };
                }
            }

            function retryInvoiceWithNewPaymentMethod({
                customerId,
                paymentMethodId,
                invoiceId,
                priceId
            })
            {
                return (
                    fetch('/api/retry-invoice', {
                        method: 'post',
                        headers: {
                            'Content-type': 'application/json',
                        },
                        body: JSON.stringify({
                            customerId: customerId,
                            paymentMethodId: paymentMethodId,
                            invoiceId: invoiceId,
                        }),
                    })
                        .then((response) => {
                            return response.json();
                        })
                        // If the card is declined, display an error to the user.
                        .then((result) => {
                            if (result.error) {
                                // The card had an error when trying to attach it to a customer.
                                throw result;
                            }
                            return result;
                        })
                        // Normalize the result to contain the object returned by Stripe.
                        // Add the additional details we need.
                        .then((result) => {
                            return {
                                // Use the Stripe 'object' property on the
                                // returned result to understand what object is returned.
                                invoice: result,
                                paymentMethodId: paymentMethodId,
                                priceId: priceId,
                                isRetry: true,
                            };
                        })
                        // Some payment methods require a customer to be on session
                        // to complete the payment process. Check the status of the
                        // payment intent to handle these actions.
                        .then(handlePaymentThatRequiresCustomerAction)
                        // No more actions required. Provision your service for the user.
                        .then(onSubscriptionComplete)
                        .catch((error) => {
                            // An error has happened. Display the failure to the user here.
                            // We utilize the HTML element we created.
                            showCardError(error);
                            showModalError(error);
                        })
                );
            }

            function onSubscriptionComplete(result)
            {
                if (result.subscription === undefined) {
                    getSubscription(result.invoice.subscription).then(onSubscriptionComplete);
                }
                if (result.subscription.status === 'incomplete') {
                    getSubscription(result.subscription.id).then(onSubscriptionComplete);
                }
                if (result.subscription.status === 'active') {
                    // Change your UI to show a success message to your customer.
                    // Call your backend to grant access to your service based on
                    // `result.subscription.items.data[0].price.product` the customer subscribed to.
                    localStorage.clear();
                    modalLoader.style.display = "none";
                    modalText.innerHTML = "Subscription completed!";
                    setInterval(function () {
                        window.location.reload(true);
                    }, 2000);
                    recordIds(result).then();
                }
            }

            function recordIds(result)
            {
                return (
                    fetch('/api/record-product-and-sub-id', {
                        method: 'post',
                        headers: {
                            'Content-type': 'application/json',
                        },
                        body: JSON.stringify({
                            customerId: customerId,
                            productId: result.subscription.items.data[0].price.product,
                            subscriptionId: result.subscription.items.data[0].subscription,
                        })
                    }).then())
            }

            function getSubscription(subscriptionId)
            {
                return (
                    fetch('/api/get-subscription-by-id', {
                        method: 'post',
                        headers: {
                            'Content-type': 'application/json',
                        },
                        body: JSON.stringify({
                            subscriptionId: subscriptionId,
                        })
                    }).then((response) => {
                        return response.json();
                    }).then((result) => {
                        if (result.error) {
                            throw result;
                        }
                        return result;
                    }).then((result) => {
                        return {
                            subscription: result,
                        };
                    }).catch((error) => {
                        showModalError(error);
                        showCardError(error);
                    })
                )
            }
            function toggleModal()
            {
                modal.classList.toggle("show-modal");
            }

            function showCardError(event)
            {
                let displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            }

            function showModalError(event)
            {
                if (event.error) {
                    modalText.innerHTML = event.error.message;
                    modalLoader.style.display = "none";
                    closeButton.style.visibility = 'visible';
                }
            }

            trigger.addEventListener("click", toggleModal);
            closeButton.addEventListener("click", function () {
                toggleModal();
                modalText.innerHTML = modalTextDefault;
                modalLoader.style.removeProperty('display');
                closeButton.style.visibility = 'hidden';
            });
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                const latestInvoicePaymentIntentStatus = localStorage.getItem(
                    'latestInvoicePaymentIntentStatus'
                );

                if (latestInvoicePaymentIntentStatus === 'requires_payment_method') {
                    const invoiceId = localStorage.getItem('latestInvoiceId');
                    const isPaymentRetry = true;
                    // create new payment method & retry payment on invoice with new payment method
                    createPaymentMethod({
                        card,
                        isPaymentRetry,
                        invoiceId,
                    });
                } else {
                    // create new payment method & create subscription
                    createPaymentMethod({ card });
                }
            });
        };
    }
}


