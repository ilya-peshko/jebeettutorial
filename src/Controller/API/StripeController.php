<?php

namespace App\Controller\API;

use App\Entity\Stripe;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class StripeController
 * @package App\Controller\API
 */
class StripeController extends BaseController
{

    /**
     * @Route("/api/create-subscription", name="stripe_subscription_create", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function subscription(Request $request): Response
    {
        $stripe = new StripeClient($this->getParameter('stripe_secret_key'));
        $body   = json_decode($request->getContent(), true);

        try {
            $payment_method = $stripe->paymentMethods->retrieve(
                $body['paymentMethodId']
            );
            $payment_method->attach([
                'customer' => $body['customerId'],
            ]);

            // Set the default payment method on the customer
            $stripe->customers->update($body['customerId'], [
                'invoice_settings' => [
                    'default_payment_method' => $body['paymentMethodId']
                ]
            ]);

            // Create the subscription
            $subscription = $stripe->subscriptions->create([
                'customer' => $body['customerId'],
                'items'    => [
                    [
                        'price' => $body['priceId'],
                    ],
                ],
                'expand'   => ['latest_invoice.payment_intent'],
            ]);

            return new JsonResponse($subscription);
        } catch (\Exception $e) {
            return new JsonResponse($e);
        }
    }

    /**
     * @Route("/api/retry-invoice", name="stripe_retry_invoice", methods={"POST"})
     *
     * @param Request $request
     * @throws ApiErrorException
     *
     * @return JsonResponse
     */
    public function retryInvoice(Request $request): JsonResponse
    {
        $stripe = new StripeClient($this->getParameter('stripe_secret_key'));
        $body = json_decode($request->getContent(), true);

        try {
            $payment_method = $stripe->paymentMethods->retrieve(
                $body['paymentMethodId']
            );
            $payment_method->attach([
                'customer' => $body['customerId'],
            ]);
        } catch (\Exception $e) {
            return new JsonResponse($e);
        }

        // Set the default payment method on the customer
        $stripe->customers->update($body['customerId'], [
            'invoice_settings' => [
                'default_payment_method' => $body['paymentMethodId']
            ]
        ]);

        $invoice = $stripe->invoices->retrieve($body['invoiceId'], [
            'expand' => ['payment_intent']
        ]);

        return new JsonResponse($invoice);
    }

    /**
     * @Route("/api/cancel-subscription", name="stripe_cancel_subscription", methods={"POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function cancelSubscription(Request $request): Response
    {
        try {
            $stripe = new StripeClient($this->getParameter('stripe_secret_key'));
            $body = json_decode($request->getContent(), true);

            /** @var Stripe $stripeEntity */
            $stripeEntity = $this->getDoctrine()->getRepository(Stripe::class)->findOneBy([
                'stripeCustomerId' => $body['customerId']
            ]);

            $subscription = $stripe->subscriptions->retrieve(
                $body['subscriptionId']
            );

            if ($subscription->status !== 'canceled') {
                $subscription->delete();
            }

            $stripeEntity->setStripeSubscriptionId(null);
            $stripeEntity->setStripeProductId(null);
            $this->getDoctrine()->getManager()->persist($stripeEntity);
            $this->getDoctrine()->getManager()->flush();
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), $e->getCode());
        }

        return new JsonResponse($subscription);
    }

    /**
     * @Route("/api/record-product-and-sub-id", name="stripe_record_id", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function record(Request $request): JsonResponse
    {
        try {
            $body = json_decode($request->getContent(), true);
            /** @var Stripe $stripeEntity */
            $stripeEntity = $this->getDoctrine()->getRepository(Stripe::class)->findOneBy([
                'stripeCustomerId' => $body['customerId']
            ]);
            $stripeEntity->setStripeProductId($body['productId']);
            $stripeEntity->setStripeSubscriptionId($body['subscriptionId']);
            $this->getDoctrine()->getManager()->persist($stripeEntity);
            $this->getDoctrine()->getManager()->flush();
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), $e->getCode());
        }

        return $this->successMessage('Success record');
    }

    /**
     * @Route("/api/get-subscription-by-id", name="stripe_get_subscription_by_id", methods={"GET","POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getSubscription(Request $request): JsonResponse
    {
        try {
            $stripe = new StripeClient($this->getParameter('stripe_secret_key'));
            $body = json_decode($request->getContent(), true);

            $subscription = $stripe->subscriptions->retrieve(
                $body['subscriptionId'],
                []
            );
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), $e->getCode());
        }

        return new JsonResponse($subscription);
    }
}
