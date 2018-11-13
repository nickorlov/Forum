<?php

namespace ForumBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use ForumBundle\Entity\Post;
use ForumBundle\Form\PostFormType;
use ForumBundle\Repository\PostRepository;
use ForumBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

        try {
            $post->setAuthor($this->userRepository->findOneByUserName('auth0-username'));
        } catch (NoResultException $e) {
            return new Response('Not found!');
        } catch (NonUniqueResultException $e) {
            return new Response('NonUniqueResultException!');
        }

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

    /**
     * @Route("/", name="homepage")
     * @Route("/posts", name="posts")
     */
    public function listAction()
    {
        return $this->render('post/posts.html.twig', [
            'posts' => $this->postRepository->findAll(),
        ]);
    }
}
