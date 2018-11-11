<?php

namespace ForumBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use ForumBundle\Entity\Post;
use ForumBundle\Form\PostFormType;
use ForumBundle\Repository\PostRepository;
use ForumBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var PostRepository */
    private $postRepository;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->postRepository = $entityManager->getRepository('ForumBundle:Post');
        $this->userRepository = $entityManager->getRepository('ForumBundle:User');
    }

    /**
     * @Route("/add-post", name="add_post")
     */
    public function createPostAction(Request $request)
    {
        $post = new Post();
        $post->setAuthor($this->userRepository->find(11));

        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($post);
            $this->entityManager->flush();

            $this->addFlash('success', 'Congratulations! The post was created!');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('post/add-post.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
