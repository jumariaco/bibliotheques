<?php

namespace App\Form;

use App\Entity\Emprunt;
use App\Entity\Emprunteur;
use App\Entity\Livre;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmpruntType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateEmprunt')
            ->add('dateRetour')
            // ->add('livres', EntityType::class, [
            //     'class' => Livre::class,
            //     'choice_label'=> function(Livre $livre){
            //         return "{$livre->getTitre()} (id: {$livre->getId()})";
            //     },
            //     'multiple' => true,
            //     'expanded' => true,
            //     'attr' => ['class' => 'form_scrollable-checkboxes'],
            //     'by_reference' => false,
            //     'query_builder' => function (EntityRepository $er) {
            //         return $er->createQueryBuilder('l')
            //             ->orderBy('l.titre', 'ASC');
                    
            //     }])
            // ->add('emprunteurs', EntityType::class, [
            //     'class' => Emprunteur::class,
            //     'choice_label'=> function(Emprunteur $emprunteur){
            //         return "{$emprunteur->getNom()} {$emprunteur->getPrenom()} (id: {$emprunteur->getId()})";
            //     },
            //     'multiple' => true,
            //     'expanded' => true,
            //     'attr' => ['class' => 'form_scrollable-checkboxes'],
            //     'by_reference' => false,
            //     'query_builder' => function (EntityRepository $er) {
            //         return $er->createQueryBuilder('e')
            //             ->orderBy('e.nom', 'ASC')
            //             ->orderBy('e.prenom', 'ASC');
            //     }])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}
