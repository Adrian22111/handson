<?php

namespace App\Security;

use DateTime;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class UserChecker implements UserCheckerInterface
{

    /**
     * @param User $user
     */
    public function checkPreAuth(UserInterface $user): void
    {
        if ($user->getBannedUntil() == null) {
            return;
        }

        $now = new DateTime();
        if ($now < $user->getBannedUntil()) {
            throw new CustomUserMessageAccountStatusException("The user is banned");
        }
    }

    /**
     * @param User $user
     */
    public function checkPostAuth(UserInterface $user): void {}
}
