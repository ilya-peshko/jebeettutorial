<?php

namespace App\Form;

use App\Entity\Resume;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
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
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('surname', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('dateOfBirthday', BirthdayType::class, [
                'placeholder' => [
                    'year' => 'Year', 'month' => 'Month', 'day' => 'Day'
                ],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('cityOfResidence', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('gender', ChoiceType::class, [
                'choices'     => [
                    'Male' => 'Male',
                    'Female' => 'Female'],
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255]),
                ]
            ])
            ->add('aboutMe', TextareaType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255]),
                ]
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Resume::class,
        ]);
    }
}
