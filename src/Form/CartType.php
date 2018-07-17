<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity', IntegerType::class, [ 'data' => '1',
                'label' => false, 'attr' => ['type'=> "number", 'min' => '1',
                    'max' => '50', 'class' => 'form-control']])
        ;
    }
}