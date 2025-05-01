<?php

namespace App\Security\Voter;

use App\Entity\MicroPost;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

final class MicroPostVoter extends Voter
{
    public function __construct(
        private AccessDecisionManagerInterface $accessDecisionManager,
    ) {}

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [MicroPost::EDIT, MicroPost::VIEW])
            && $subject instanceof \App\Entity\MicroPost;
    }

    /** 
     * @param MicroPost $subject 
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        /** @var User $user */
        $user = $token->getUser();

        $isAuth = $user instanceof UserInterface;

        if ($this->accessDecisionManager->decide($token, ['ROLE_ADMIN']))
            return true;

        switch ($attribute) {
            case MicroPost::EDIT:
                return $isAuth && (
                    $subject->getAuthor()->getId() == $user->getId()
                    || $this->accessDecisionManager->decide($token, ['ROLE_EDITOR'])
                );
                break;

            case MicroPost::VIEW:
                return true;
        }

        return false;
    }
}
