<?php

namespace App\Form;

use App\Entity\Formation;
use Proxies\__CG__\App\Entity\Niveaux;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('publishedAt', null, [
                'required' => true
                
            ])
            ->add('title', null, [
                'required' => true
            ])
            ->add('description')
            ->add('miniature')
            ->add('picture')
            ->add('videoId')
            ->add('niveau', EntityType::class, [
                'class' => Niveaux::class,
                'choice_label' => 'nom',
                'multiple' => false,
                'required' => true
                
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
