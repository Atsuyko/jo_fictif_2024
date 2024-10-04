<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

final readonly class OAuthRegistrationService
{
  public function __construct(private EntityManagerInterface $em) {}
  /**
   * Undocumented function
   *
   * @param GoogleUser $resourceOwnerInterface
   * @return User
   */
  public function persist(ResourceOwnerInterface $resourceOwnerInterface): User
  {
    $user = (new User())
      ->setEmail($resourceOwnerInterface->getEmail())
      ->setGoogleId($resourceOwnerInterface->getId())
      ->setFirstname($resourceOwnerInterface->getFirstName())
      ->setLastname($resourceOwnerInterface->getLastName())
      ->setVerified(true)
      ->setPlainPassword($resourceOwnerInterface->getId());

    $this->em->persist($user);
    $this->em->flush($user);

    return $user;
  }
}
