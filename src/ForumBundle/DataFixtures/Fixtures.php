<?php

namespace ForumBundle\DataFixtures;

use ForumBundle\Entity\Category;
use ForumBundle\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use ForumBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Fixtures extends Fixture implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $userManager = $this->container->get('fos_user.user_manager');

        /** @var User $user */
        $user = $userManager->createUser();
        $user
            ->setName('Joe Blogs')
            ->setUsername('orel95')
            ->setShortBio('Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages.')
            ->setFacebook('joebloggs')
            ->setEmail('joe@gmail.com')
            ->setPlainPassword('111111')
            ->setBirthday(new \DateTime('09-01-1995'))
            ->setEnabled(true)
            ->setRoles(['ROLE_ADMIN'])
            ->setAvatar('noavatar.jpg');

        $userManager->updateUser($user, false);

        $cars = new Category();
        $cars->setTitle('Cars');
        $cars->setDescription('Topics about cars');
        $bmw = new Category();
        $bmw->setTitle('BMW');
        $bmw->setParent($cars);
        $audi = new Category();
        $audi->setTitle('AUDI');
        $audi->setParent($cars);
        $articles = new Category();
        $articles->setTitle('Articles');
        $articles->setDescription('Just articles');

        $post = new Post();
        $post
            ->setTitle('Your first blog post example')
            ->setBody('Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur, from a Lorem Ipsum passage, and going through the cites of the word in classical literature, discovered the undoubtable source. Lorem Ipsum comes from sections 1.10.32 and 1.10.33 of "de Finibus Bonorum et Malorum" (The Extremes of Good and Evil) by Cicero, written in 45 BC. This book is a treatise on the theory of ethics, very popular during the Renaissance. The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", comes from a line in section 1.10.32. The standard chunk of Lorem Ipsum used since the 1500s is reproduced below for those interested. Sections 1.10.32 and 1.10.33 from "de Finibus Bonorum et Malorum" by Cicero are also reproduced in their exact original form, accompanied by English versions from the 1914 translation by H. Rackham.')
            ->setAuthor($user)
            ->setCategory($cars);

        $manager->persist($cars);
        $manager->persist($bmw);
        $manager->persist($audi);
        $manager->persist($articles);
        $manager->persist($post);
        $manager->persist($user);
        $manager->flush();
    }
}