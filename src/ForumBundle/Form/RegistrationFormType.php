<?php

namespace ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** todo Add validation */
        $builder->add('name');
        $builder->add('shortBio');
        $builder->add('birthday');
        $builder->add('facebook');
        $builder->add('avatar');
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'forum_user_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
