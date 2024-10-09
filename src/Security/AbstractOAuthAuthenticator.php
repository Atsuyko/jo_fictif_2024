<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use KnpU\OAuth2ClientBundle\Client\OAuth2ClientInterface;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

abstract class AbstractOAuthAuthenticator extends OAuth2Authenticator
{
    use TargetPathTrait;

    protected string $serviceName = '';

    public function __construct(
        private readonly ClientRegistry $clientRegistry,
        private readonly RouterInterface $routerInterface,
        private readonly UserRepository $userRepository,
        private readonly OAuthRegistrationService $oAuthRegistrationService,
    ) {}

    /**
     * Return route for connect with oauth2
     *
     * @param Request $request
     * @return boolean|null
     */
    public function supports(Request $request): ?bool
    {
        return 'check' === $request->attributes->get('_route') && $request->get('service') === $this->serviceName;
    }

    /**
     * Create or Log in user with oauth2
     *
     * @param Request $request
     * @return SelfValidatingPassport
     */
    public function authenticate(Request $request): SelfValidatingPassport
    {
        $credentials = $this->fetchAccessToken(($this->getClient()));
        $resourceOwner = $this->getResourceOwnerFromCredentials($credentials);
        $user = $this->getUserFromResourceOwner($resourceOwner, $this->userRepository);

        if (null === $user) {
            $user = $this->oAuthRegistrationService->persist($resourceOwner);
        }

        return new SelfValidatingPassport(
            userBadge: new UserBadge($user->getUserIdentifier(), fn() => $user),
            badges: [
                new RememberMeBadge()
            ]
        );
    }

    /**
     * Log In Success
     *
     * @param Request $request
     * @param TokenInterface $token
     * @param string $firewallName
     * @return Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        $targetPath = $this->getTargetPath($request->getSession(), $firewallName);
        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }

        return new RedirectResponse($this->routerInterface->generate('login'));
    }

    /**
     * Log In Fail
     *
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        if ($request->hasSession()) {
            $request->getSession()->set(SecurityRequestAttributes::AUTHENTICATION_ERROR, $exception);
        }

        return new RedirectResponse($this->routerInterface->generate('login'));
    }

    /**
     * Get name of the service (google, github ...)
     *
     * @return OAuth2ClientInterface
     */
    private function getClient(): OAuth2ClientInterface
    {
        return $this->clientRegistry->getClient($this->serviceName);
    }

    /**
     * Get information from oauth service
     *
     * @param AccessToken $accessToken
     * @return ResourceOwnerInterface
     */
    private function getResourceOwnerFromCredentials(AccessToken $accessToken): ResourceOwnerInterface
    {
        return $this->getClient()->fetchUserFromToken($accessToken);
    }

    abstract protected function getUserFromResourceOwner(ResourceOwnerInterface $resourceOwnerInterface, UserRepository $userRepository): ?User;
}
