<?php

namespace App\Controller;

use DateTime;
use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Form\CommentType;
use App\Form\MicroPostType;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $posts, EntityManagerInterface $entityManager): Response
    {
        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts->findAllWithComments()
        ]);
    }

    #[Route('/micro-post/{post<\d+>}', name: 'app_micro_post_show')]
    public function showOne(MicroPost $post): Response
    {
        return $this->render('micro_post/show.html.twig', [
            'post' => $post
        ]);
    }

    #[Route('micro-post/add', name: 'app_micro_post_add', priority: 2)]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MicroPostType::class, new MicroPost);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();
            $post->setCreated(new DateTime());
            $post->setAuthor($this->getUser());

            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'Your micropost has been added');

            return $this->redirectToRoute('app_micro_post');
        }

        return $this->render(
            'micro_post/add.html.twig',
            [
                'form' => $form,
            ]
        );
    }

    #[Route('micro-post/{post<\d+>}/edit', name: 'app_micro_post_edit', priority: 2)]
    public function edit(MicroPost $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MicroPostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post = $form->getData();

            $entityManager->persist($post);
            $entityManager->flush();

            $this->addFlash('success', 'Your micropost has beqen edited');

            return $this->redirectToRoute('app_micro_post');
        }

        return $this->render(
            'micro_post/edit.html.twig',
            [
                'form' => $form,
                'post' => $post
            ]
        );
    }

    #[Route('micro-post/{post<\d+>}/comment', name: 'app_micro_post_comment', priority: 2)]
    public function addComment(MicroPost $post, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CommentType::class, new Comment());

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setPost($post);
            $comment->setAuthor($this->getUser());

            $entityManager->persist($comment);
            $entityManager->flush();

            $this->addFlash('success', 'Your comment has beqen edited');

            return $this->redirectToRoute(
                'app_micro_post_show',
                ['post' => $post->getId()]
            );
        }

        return $this->render(
            'micro_post/comment.html.twig',
            [
                'form' => $form,
                'post' => $post
            ]
        );
    }
}
