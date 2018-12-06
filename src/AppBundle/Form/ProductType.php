<?php

namespace AppBundle\Form;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('price', NumberType::class, array('label' => 'Price(€)'))
            ->add('description', TextareaType::class, array('attr' => array('rows' => 10, 'cols' => 22)))
            ->add('stock_quantity', NumberType::class, array('label' => 'Stock quantity'))
            ->add('categories', EntityType::class, [
                'class'        => Category::class,
                'choice_label' => 'name',
                'expanded'     => true,
                'multiple'     => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults(array('data_class' => Product::class))
        ;
    }
}