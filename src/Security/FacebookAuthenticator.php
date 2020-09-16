<?php

namespace App\Security;

use App\Entity\Stripe;
use App\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Token\AccessToken;
use Stripe\StripeClient;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class FacebookAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $em;
    private $router;
    private $resetPasswordHelper;
    private $mailer;
    private $passwordEncoder;
    private $params;

    /**
     * FacebookAuthenticator constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param MailerInterface $mailer
     * @param ResetPasswordHelperInterface $resetPasswordHelper
     * @param ClientRegistry $clientRegistry
     * @param EntityManagerInterface $em
     * @param RouterInterface $router
     * @param ParameterBagInterface $params
     */
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        MailerInterface $mailer,
        ResetPasswordHelperInterface $resetPasswordHelper,
        ClientRegistry $clientRegistry,
        EntityManagerInterface $em,
        RouterInterface $router,
        ParameterBagInterface $params
    ) {
        $this->clientRegistry      = $clientRegistry;
        $this->em                  = $em;
        $this->router              = $router;
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->mailer              = $mailer;
        $this->passwordEncoder     = $passwordEncoder;
        $this->params              = $params;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request): bool
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'connect_facebook_check';
    }

    /**
     * @param Request $request
     * @return AccessToken|mixed
     */
    public function getCredentials(Request $request)
    {
        // this method is only called if supports() returns true

        // For Symfony lower than 3.4 the supports method need to be called manually here:
        // if (!$this->supports($request)) {
        //     return null;
        // }

        return $this->fetchAccessToken($this->getFacebookClient());
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @throws TransportExceptionInterface*@throws \Exception
     * @throws ResetPasswordExceptionInterface
     * @throws Exception
     *
     * @return User|object|UserInterface|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var FacebookUser $facebookUser */
        $facebookUser = $this->getFacebookClient()
            ->fetchUserFromToken($credentials);

        $email = $facebookUser->getEmail();

        $existingUser = $this->em->getRepository(User::class)
            ->findOneBy(['facebookId' => $facebookUser->getId()]);
        if ($existingUser) {
            return $existingUser;
        }

        /** @var User $user */
        $user = $this->em->getRepository(User::class)
            ->findOneBy(['email' => $email]);

        if ($user === null) {
            $user = new User();
            $stripeEntity = new Stripe();
            $password = $this->generateRandomPassword(8);
            $user->setEmail($email)
                ->setUsername($email)
                ->setFacebookId($facebookUser->getId())
                ->setEnabled(true)
                ->setIsVerified(true)
                ->setPassword($this->passwordEncoder->encodePassword($user, $password))
                ->setRoles(['ROLE_APPLICANT']);

            $stripe = new StripeClient($this->params->get('stripe_secret_key'));
            $stripeCustomer = $stripe->customers->create([
                'email'       => $user->getEmail(),
                'description' => $user->getUsername()
            ]);
            $stripeEntity->setUser($user)->setStripeCustomerId($stripeCustomer->id);

            $this->em->persist($user);
            $this->em->persist($stripeEntity);
            $this->em->flush();

            $resetToken = $this->resetPasswordHelper->generateResetToken($user);

            $email_password = (new TemplatedEmail())
                ->from(new Address('alkatras4321@gmail.com', 'Jobeet'))
                ->to($user->getEmail())
                ->subject('Your password reset request')
                ->htmlTemplate('reset_password/facebook_password_email.html.twig')
                ->context([
                    'password'      => $password,
                    'resetToken'    => $resetToken,
                    'tokenLifetime' => $this->resetPasswordHelper->getTokenLifetime(),
                ]);
            $this->mailer->send($email_password);

            return $user;
        }

        $user->setFacebookId($facebookUser->getId());
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /**
     * @return OAuth2ClientInterface
     */
    private function getFacebookClient(): OAuth2ClientInterface
    {
        return $this->clientRegistry
            ->getClient('facebook');
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return RedirectResponse|Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // change "app_homepage" to some route in your app
        $targetUrl = $this->router->generate('job_list');

        return new RedirectResponse($targetUrl);

        // or, on success, let the request continue to be handled by the controller
        //return null;
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     *
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return RedirectResponse|Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse(
            '/connect/', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }

    /**
     * @param $length
     * @return string
     * @throws Exception
     */
    public function generateRandomPassword($length): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
