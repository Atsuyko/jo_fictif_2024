<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class GoogleAuthenticator extends AbstractOAuthAuthenticator
{
    protected string $serviceName = 'google';

    protected function getUserFromResourceOwner(ResourceOwnerInterface $resourceOwnerInterface, UserRepository $userRepository): ?User
    {
        if (!($resourceOwnerInterface instanceof GoogleUser)) {
            throw new \RuntimeException("expecting google user");
        }

        if (true !== ($resourceOwnerInterface->toArray()['email_verified']) ?? null) {
            throw new AuthenticationException("email not verified");
        }

        return $userRepository->findOneBy([
            'google_id' => $resourceOwnerInterface->getId(),
            'email' => $resourceOwnerInterface->getEmail()
        ]);
    }
}
