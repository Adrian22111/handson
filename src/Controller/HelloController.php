<?php

namespace App\Controller;

use DateTime;
use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


class HelloController extends AbstractController
{
    private array $messages = [
        ['message' => 'Hello', 'created' => '2025/03/12'],
        ['message' => 'Hi', 'created' => '2025/02/12'],
        ['message' => 'Bye!', 'created' => '2021/05/12'],
    ];

    #[Route('/{limit<\d+>?3}', name: 'app_index')]
    public function index(int $limit, EntityManagerInterface $entityManager, MicroPostRepository $posts, MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('hello@example.com')
            ->to('you@example.com')
            ->subject('Test mail')
            ->text('Sending mails is fun!');

        $mailer->send($email);
        // $post = new MicroPost();
        // $post->setTitle('Hello');
        // $post->setText('Hello');
        // $post->setCreated(new DateTime());

        // $comment = new Comment();
        // $comment->setText('Hello');

        // $post = $posts->find(14);
        // $post->getComments()->count();
        // $comment->setPost($post);

        // $entityManager->persist($post);
        // $entityManager->persist($comment);
        // $entityManager->flush();


        return $this->render(
            '/hello/index.html.twig',
            [
                'messages' => $this->messages,
                'limit' => $limit
            ]
        );
    }

    #[Route('/messages/{id<\d+>}', name: 'app_show_one')]
    public function showOne(int $id): Response
    {
        return $this->render(
            'hello/show_one.html.twig',
            [
                'message' => $this->messages[$id]
            ]
        );
    }
}
