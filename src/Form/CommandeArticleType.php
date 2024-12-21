<?php

namespace App\Form;

use App\Entity\CommandeArticle;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class CommandeArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('article', ChoiceType::class, [
                'choices' => $options['articles'], // Passer les articles disponibles
                'label' => 'Sélectionner un article',
            ])
            ->add('quantite', IntegerType::class, [
                'label' => 'Quantité',
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix',
            ]);
    }
}