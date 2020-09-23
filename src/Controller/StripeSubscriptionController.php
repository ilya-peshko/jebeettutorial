<?php

namespace App\Controller;

use App\Entity\User\User;
use Stripe\Exception\ApiErrorException;
use Stripe\StripeClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class StripeSubscriptionController
 * @package App\Controller
 */
class StripeSubscriptionController extends AbstractController
{
    /**
     * @Route("/{_locale<en|ru>}/subscription", name="stripe_subscription")
     *
     * @throws ApiErrorException
     *
     * @return Response
     */
    public function index(): Response
    {
        /** @var User $user */
        $user     = $this->getUser();
        $stripe   = new StripeClient($this->getParameter('stripe_secret_key'));
        $products = [];
        $subscriptionId = $user->getStripe()->getStripeSubscriptionId();

        if ($subscriptionId === null) {
            $prices = $stripe->prices->all();
            foreach ($prices as $price) {
                $products[] = [
                    'price'     => $price->unit_amount,
                    'priceId'   => (string)$price->id,
                    'product'   => $stripe->products->retrieve($price->product)->name,
                    'productId' => $stripe->products->retrieve($price->product)->id,
                ];
            }

            return $this->render('stripe/subscribe.html.twig', [
                'pk_key'           => $this->getParameter('stripe_publishable_key'),
                'stripeCustomerId' => $user->getStripe()->getStripeCustomerId(),
                'products'         => $products,
            ]);
        }

        $subscription = $stripe->subscriptions->retrieve($subscriptionId);
        $product = $stripe->products->retrieve($user->getStripe()->getStripeProductId());
        $subInfo = [
            'endDate'   => $subscription->current_period_end,
            'startDate' => $subscription->current_period_start,
            'product'   => $product->name,
            'productId' => $product->id,
        ];

        return $this->render('stripe/subscribe_info.html.twig', [
            'pk_key'               => $this->getParameter('stripe_publishable_key'),
            'stripeCustomerId'     => $user->getStripe()->getStripeCustomerId(),
            'stripeSubscriptionId' => $user->getStripe()->getStripeSubscriptionId(),
            'subInfo'              => $subInfo,
        ]);
    }
}
