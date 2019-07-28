<?php

// namespace App\Form;

// use Symfony\Component\Form\AbstractType;
// use Symfony\Component\Form\FormBuilderInterface;
// use Symfony\Component\OptionsResolver\OptionsResolver;
// use Symfony\Component\Form\Extension\Core\Type\TextareaType;
// use Symfony\Component\Form\Extension\Core\Type\SubmitType;
// use App\Entity\MicroPost;

// class MicroPostType extends AbstractType
// {
//     public function buildForm(FormBuilderInterface $builder, array $options)
//     {
//         $builder->add('text', TextareaType::class, ['label' => false])
//             ->add('save', SubmitType::class);
//     }
//     public function configureOptions(OptionsResolver $resolver)
//     {
//         // $resolver->setDefault(array('data_class'=>MicroPost::class));
//         $resolver->setDefaults([
//             'data_class' => MicroPost::class
//         ]);
//     }
// }
namespace App\Form;

use App\Entity\MicroPost;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MicroPostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('text', TextareaType::class, ['label' => false])
            ->add('save', SubmitType::class, ['label' => 'Create Post']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MicroPost::class,
        ]);
    }
}
