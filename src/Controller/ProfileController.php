<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProfileController extends AbstractController
{
    #[Route('/profile/{id<\d+>}', name: 'app_profile')]
    public function show(
        User $user,
        MicroPostRepository $posts    
    ): Response
    {
        return $this->render('profile/show.html.twig', [
            'user' => $user,
            'posts' => $posts->findAllByAuthor(
                $user
            )
        ]);
    }
    #[Route('/profile/{id<\d+>}/follows', name: 'app_profile_follows')]
    public function follows(User $user): Response
    {
        return $this->render('profile/follows.html.twig', [
            'user' => $user,
        ]);
    }
    #[Route('/profile/{id<\d+>}/followers', name: 'app_profile_followers')]
    public function followers(User $user): Response
    {
        return $this->render('profile/followers.html.twig', [
            'user' => $user,
        ]);
    }
}
