<?php

namespace ForumBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Entity\Repository\CategoryRepository;
use ForumBundle\Entity\Category;
use ForumBundle\Entity\Comment;
use ForumBundle\Entity\Post;
use ForumBundle\Form\CategoryFormType;
use ForumBundle\Form\CommentFormType;
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

    /** @var CategoryRepository */
    private $categoryRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->postRepository = $entityManager->getRepository('ForumBundle:Post');
        $this->userRepository = $entityManager->getRepository('ForumBundle:User');
        $this->categoryRepository = $entityManager->getRepository('ForumBundle:Category');
    }

    /**
     * @Route("/add-post", name="add_post")
     */
    public function createPostAction(Request $request)
    {
        $post = new Post();
        $post->setAuthor($this->getUser());

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
     * @Route("/posts", name="posts")
     */
    public function listAction()
    {
        return $this->render('post/posts.html.twig', [
            'posts' => $this->postRepository->findAll(),
        ]);
    }

    /**
     * @Route("/post/{id}", name="showPost", requirements={"id"="\d+"}, methods={"GET"})
     * @param int $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function showPostAction($id)
    {
        $post = $this->postRepository->find($id);

        return $this->render('post/post.html.twig', [
            'post' => $post,
            'form' => ($this->createForm(CommentFormType::class))->createView()
        ]);
    }

    /**
     * @Route("/post/{id}", name="createComment", requirements={"id"="\d+"}, methods={"POST"})
     * @param int $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createCommentAction($id, Request $request)
    {
        $post = $this->postRepository->find($id);
        $comment = new Comment();
        $comment->setAuthor($this->getUser());
        $comment->setPost($post);

        $form = $this->createForm(CommentFormType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setComment($comment);
            $this->entityManager->persist($comment);
            $this->entityManager->persist($post);
            $this->entityManager->flush();

            $this->addFlash('success', 'Congratulations! Your comment was added!');

            return $this->redirectToRoute('showPost', ['id' => $id]);
        }

        return $this->render('post/post.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/category/{id}", name="category", requirements={"id"="\d+"})
     */
    public function categoryAction($id)
    {
        $category = $this->categoryRepository->find($id);
        $posts = $this->postRepository->findBy(['category' => $id]);

        $repo = $this->getDoctrine()->getRepository('ForumBundle:Category');
        $options = array(
            'decorate' => true,
            'rootOpen' => '<ul>',
            'rootClose' => '</ul>',
            'childOpen' => '<li>',
            'childClose' => '</li>',
            'nodeDecorator' => function ($node) {
                $decorator = '<a href="/category/' . $node['id'] . '">' . $node['title'] . '</a>';
                if ($node['description']) {
                    $decorator .= '<p>' . $node['description'] . '</p>';
                }

                return $decorator;
            }
        );
        $htmlTree = $repo->childrenHierarchy(
            $category,
            false,
            $options
        );

        return $this->render('post/category.html.twig', [
            'category' => $category,
            'tree' => $htmlTree,
            'posts' => $posts
        ]);
    }

    /**
     * @Route("/create-category", name="create_category")
     */
    public function createCategoryAction(Request $request)
    {
        $category = new Category();

        $form = $this->createForm(CategoryFormType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($category);
            $this->entityManager->flush();

            $this->addFlash('success', 'Congratulations! The category was created!');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('post/add-category.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
