<?php


namespace App\Entity\Traits;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;

trait FormTrait
{
    /**
     * @return FormInterface
     */
    public function createSearchForm(): FormInterface
    {
        return $this->createFormBuilder(null)
            ->add('Request',TextType::class,[
                'required' => false,
            ])
            ->add('search', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
            ])
            ->getForm();
    }
}