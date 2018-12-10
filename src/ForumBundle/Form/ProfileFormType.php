<?php

namespace ForumBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ProfileFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'avatar',
                FileType::class,
                [
                    'attr' => ['class' => 'form-control'],
                    'data_class' => null,
                    'required' => false
                ]
            )
            ->add(
                'birthday',
                BirthdayType::class,
                ['attr' => ['class' => 'form-control']]
            )
            ->add(
                'facebook',
                TextType::class,
                ['attr' => ['class' => 'form-control']]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'attr' => ['class' => 'form-control btn-primary pull-right'],
                    'label' => 'Submit'
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'ForumBundle\Entity\User'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'profile_form';
    }
}