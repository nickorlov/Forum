<?php

namespace ForumBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use ForumBundle\Entity\Post;
use ForumBundle\Form\PostFormType;
use ForumBundle\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class PostController extends Controller
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var PostRepository */
    private $postRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->postRepository = $entityManager->getRepository('ForumBundle:Post');
    }

    /**
     * @Route("/add-post", name="add_post")
     */
    public function createPostAction(Request $request)
    {
        $post = new Post();
        $post->setTitle('Hello world');
        $post->setAuthor($this->getUser());
        $post->setSlug('hello_world');


        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($post);
            $this->entityManager->flush();

            $request->getSession()->set('user_is_author', true);
            $this->addFlash('success', 'Congratulations! You are now an author.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('post/add-post.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
