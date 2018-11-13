<?php

namespace ForumBundle\Controller;

//use ForumBundle\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/aaa", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository('ForumBundle:Category');
        $options = array(
            'decorate' => true,
            'rootOpen' => '<ul>',
            'rootClose' => '</ul>',
            'childOpen' => '<li>',
            'childClose' => '</li>'
        );
        $htmlTree = $repo->childrenHierarchy(
            null, /* starting from root nodes */
            false, /* true: load all children, false: only direct */
            $options
        );

//        $em = $this->get('doctrine.orm.default_entity_manager');
//        /** @var Post $post */
//        $post = $em->getRepository('ForumBundle:Post')->findOneBy(['title' => 'Your first blog post example!']);
//        $post->setTitle('Hello world!');
//        $em->persist($post);
//        $em->flush();

        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'tree' => $htmlTree,
        ]);
    }
}
