<?php

namespace App\Form;

use App\Entity\Episode;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('num', TextType::class, [
                'label' => 'Numéro de l\'article',
                'required' => true
            ])
            ->add('titre', TextType::class, [
                'label' => 'Titre de l\'article',
                'required' => true
            ])
            ->add('resume', TextType::class, [
                'label' => 'Résumé de l\'article',
                'required' => true
            ])
            ->add('principal', ChoiceType::class, [
                'label' => 'principal',
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ],
                'required' => true
            ])
            ->add('contenu', TextType::class, [
                'label' => 'Contenu de l\'article',
                'required' => false
            ])
            ->add('acte', IntegerType::class, [
                'label' => 'Acte',
                'required' => true
            ])
            ->add('post', SubmitType::class, [
                'label' => 'Créer',
                'attr' => ['class' => 'save'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Episode::class,
        ]);
    }
}
