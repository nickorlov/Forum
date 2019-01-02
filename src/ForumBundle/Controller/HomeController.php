<?php

namespace ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
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
            null,
            false,
            $options
        );

        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
            'tree' => $htmlTree,
        ]);
    }
}
