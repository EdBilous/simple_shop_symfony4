<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\ProductImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['attr' => [
                'class' => 'form-control']])
            ->add('description', null, ['attr' => [
                'class' => 'form-control']])
            ->add('price', null, ['attr' => [
                'class' => 'form-control']])
            ->add('brand', null, ['attr' => [
                'class' => 'form-control']])
            ->add('category', null, ['attr' => [
                'class' => 'form-control']])
//            ->add('discount')
            ->add('images', CollectionType::class, array(
                'entry_type' => ProductImageType::class,
                'allow_add'    => true,
                'entry_options' => array('label' => false),
                'by_reference' => false,
                'allow_delete' => true))
            ->add('submit', SubmitType::class, [
                'label' => 'Create', 'attr' => [
                'class' => 'submit']]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
