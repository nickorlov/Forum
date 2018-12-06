<?php

namespace ForumBundle\Form;

use ForumBundle\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::class,
                ['attr' => ['class' => 'form-control']]
            )
            ->add(
                'description',
                TextareaType::class,
                ['attr' => ['class' => 'form-control']]
            )
            ->add(
                'parent',
                EntityType::class,
                [
                    'class' => 'ForumBundle\Entity\Category',
                    'choice_label' => function ($category) {
                        /** @var Category $category */
                        return str_repeat('— ', $category->getLevel()) . $category->getTitle();
                    },
                    'placeholder' => 'None',
                    'required' => false
                ]
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
            'data_class' => 'ForumBundle\Entity\Category'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'category_form';
    }
}