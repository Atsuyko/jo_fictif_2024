<?php

namespace App\EntityListener;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserListener
{
  private UserPasswordHasherInterface $hasher;

  public function __construct(UserPasswordHasherInterface $hasher)
  {
    $this->hasher = $hasher;
  }

  /**
   * Hash the plain password before set into password
   *
   * @param User $user
   * @return void
   */
  public function encodePassword(User $user)
  {
    if ($user->getPlainPassword() === null) {
      return;
    }

    $user->setPassword(
      $this->hasher->hashPassword(
        $user,
        $user->getPlainPassword()
      )
    );

    $user->setPlainPassword('');
  }

  public function prePersist(User $user)
  {
    $this->encodePassword($user);
  }

  public function preUpdate(User $user)
  {
    $this->encodePassword($user);
    $user->setUpdatedAt(new \DateTimeImmutable());
  }
}
