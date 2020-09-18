<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\Request;

class FacebookController extends AbstractController
{
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/facebook", name="connect_facebook_start")
     *
     * @param ClientRegistry $clientRegistry
     * @return RedirectResponse
     */
    public function connectAction(ClientRegistry $clientRegistry): RedirectResponse
    {
        // will redirect to Facebook!
        return $clientRegistry
            ->getClient('facebook') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect([
                'public_profile', 'email', 'user_gender', 'user_hometown', 'user_birthday' // the scopes you want to access
            ]);
    }

    /**
     * After going to Facebook, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @Route("/connect/facebook/check", name="connect_facebook_check")
     *
     * @param Request $request
     * @param ClientRegistry $clientRegistry
     */
    public function connectCheckAction(Request $request, ClientRegistry $clientRegistry): void
    {
//         ** if you want to *authenticate* the user, then
//         leave this method blank and create a Guard authenticator
//         (read below)
//
//        /** @var FacebookClient $client */
//        $client = $clientRegistry->getClient('facebook');
//
//        try {
//            // the exact class depends on which provider you're using
//            /** @var FacebookUser $user */
//            $user = $client->fetchUser();
//            $accessToken = $client->getAccessToken();
//            $_SESSION['accessToken'] = $accessToken;
//            // do something with all this new power!
//            // e.g. $name = $user->getFirstName();
//            die;
//            // ...
//        } catch (IdentityProviderException $e) {
//            // something went wrong!
//            // probably you should return the reason to the user
//            die;
//        }
    }
}
