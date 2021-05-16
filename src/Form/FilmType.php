<?php

namespace App\Form;

use App\Entity\CategorieFilm;
use App\Entity\Departement;
use App\Entity\Film;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre',TextType::class,[
                'attr'=>[
                    'class'=>"form-control",
                    'placeholder'=>"saisir le titre"
                ]
            ])
            ->add('description',TextareaType::class,[
                'attr'=>[
                    'class'=>"form-control",
                    'placeholder'=>"saisir la description"
                ]
            ])
            ->add('duree',DateTimeType::class,[
                'attr'=>[
                    'class'=>'flatpickr flatpickr-input form-control'
                ]
            ])
            ->add('datesortie',DateTimeType::class,[
                'attr'=>[
                    'date_widget' => 'single_text',
                    'class'=>"form-control"
                ]
            ])
            ->add('note',IntegerType::class,[
                'attr'=>[
                    'class'=>"form-control",
                    'placeholder'=>"saisir note (d'après source)"
                ]
            ])
            ->add('realisepar',TextType::class,[
                'attr'=>[
                    'class'=>"form-control",
                    'placeholder'=>"saisir le nom du réalisateur"
                ]
            ])
            ->add('image',FileType::class,[
                'attr'=>[
                    'class'=>"form-control"
                ]
            ])
            ->add('categorie',EntityType::class,[
                'attr'=>[
                    'class'=>"form-control department-name select2input"],
                    'class' =>CategorieFilm::class ,
                    'choice_label' =>'type'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
        ]);
    }
}
