<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Livre;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GenreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add('livres', EntityType::class, [
                'class' => Livre::class,
                'choice_label'=> function(Livre $livre){
                    return "{$livre->getTitre()} (id: {$livre->getId()})";
                },
                'multiple' => true,
                'expanded' => true,
                'attr' => ['class' => 'form_scrollable-checkboxes'],
                'by_reference' => false,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('l')
                        ->orderBy('l.titre', 'ASC');
                    
                }])
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Genre::class,
        ]);
    }
}
