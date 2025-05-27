<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

final class FollowerController extends AbstractController
{
    #[Route('/follow/{id<\d+>}', name: 'app_follow')]
    public function follow(
        User $userToFollow,
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $currentUser = $this->getUser();
        /** @var User $currentUser */
        if ($userToFollow->getId() !== $currentUser->getId()) {
            $currentUser->follow($userToFollow);
            $doctrine->getManager()->flush();
        }
        return $this->redirect($request->headers->get('referer'));
    }

    #[Route('/unfollow/{id<\d+>}', name: 'app_unfollow')]
    public function unfollow(
        User $userToUnfollow,
        ManagerRegistry $doctrine,
        Request $request
    ): Response {
        $currentUser = $this->getUser();
        /** @var User $currentUser */

        if ($userToUnfollow->getId() !== $currentUser->getId()) {
            $currentUser->unfollow($userToUnfollow);
            $doctrine->getManager()->flush();
        }
        return $this->redirect($request->headers->get('referer'));
    }
}
