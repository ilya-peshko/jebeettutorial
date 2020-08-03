<?php

namespace App\Form;

use App\Entity\Resume;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ResumeType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType ::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255]),
                ]
            ])
            ->add('aboutMe', TextType ::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Resume::class,
        ]);
    }
}
