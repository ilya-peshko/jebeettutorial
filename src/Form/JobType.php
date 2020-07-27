<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Job;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * Class JobType
 * @package App\Form
 */
class JobType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices'     => array_combine(Job::TYPES, Job::TYPES),
                'expanded'    => true,
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('company', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255]),
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'required'        => false,
                'allow_delete'    => true,
                'download_label'  => false,
                'download_uri'    => false,
                'image_uri'       => true,
                'asset_helper'    => true,
                'imagine_pattern' => 'image_320x240',
            ])
            ->add('url', UrlType::class, [
                'required'    => false,
                'constraints' => [
                    new Length(['max' => 255]),
                ]
            ])
            ->add('position', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255]),
                ]
            ])
            ->add('location', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255]),
                ]
            ])
            ->add('description', TextareaType::class, [
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('howToApply', TextType::class, [
                'label'       => 'How to apply?',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('public', ChoiceType::class, [
                'choices'     => [
                    'Yes' => true,
                    'No'  => false,
                ],
                'label'       => 'Public?',
                'constraints' => [
                    new NotNull(),
                ]
            ])
            ->add('activated', ChoiceType::class, [
                'choices'     => [
                    'Yes' => true,
                    'No'  => false,
                ],
                'constraints' => [
                    new NotNull(),
                ]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email()
                ]
            ])
            ->add('category', EntityType::class, [
                'class'        => Category::class,
                'choice_label' => 'name',
                'constraints'  => [
                    new NotBlank(),
                ]
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
        ]);
    }
}
