<?php

namespace ForumBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use ForumBundle\Entity\User;
use ForumBundle\Form\ProfileFormType;
use ForumBundle\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /** @var EntityManagerInterface */
    private $entityManager;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository('ForumBundle:User');
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profileAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        $oldAvatar = $user->getAvatar();
        $form = $this->createForm(ProfileFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getAvatar()) {
                /** @var UploadedFile $avatar */
                $avatar = $user->getAvatar();
                $fileName = $this->generateUniqueFileName() . '.' . $avatar->guessExtension();
                $avatar->move(
                    $this->getParameter('avatars_directory'),
                    $fileName
                );
                $user->setAvatar($fileName);
            } else {
                $user->setAvatar($oldAvatar);
            }

            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'Congratulations! Your profile has updated!');

            return $this->redirect($this->generateUrl('profile'));
        }

        return $this->render('profile/profile.html.twig', array(
            'form' => $form->createView(),
            'user' => $user
        ));
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }

}
