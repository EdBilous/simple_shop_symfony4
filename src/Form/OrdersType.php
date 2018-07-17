<?php

namespace App\Form;

use App\Entity\Orders;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('totalPrice', HiddenType::class)
            ->add('userName',null, ['label' => "Name", 'attr' => [
                'placeholder' => 'First Last',
                'class' => 'form-control']])
            ->add('phone',null, ['label' => "Phone", 'attr' => [
                'placeholder' => '380507654321',
                'class' => 'form-control']])
            ->add('address',null, ['label' => "Address", 'attr' => [
                'placeholder' => 'Leninska str. 43/108',
                'class' => 'form-control']])
            ->add('city',null, ['label' => "City", 'attr' => [
                'placeholder' => 'Kharkiv',
                'class' => 'form-control']])
            ->add('comment', TextareaType::class, ['required'   => false,
                'label' => "Comment", 'attr' => [
                'placeholder' => '',
                'class' => 'form-control']])
            ->add('submit', SubmitType::class, [
                'label' => 'Send', 'attr' => [
                    'class' => 'hvr-skew-backward']]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Orders::class,
        ]);
    }
}
