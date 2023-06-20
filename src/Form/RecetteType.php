<?php

namespace App\Form;

use App\Entity\Recette;
use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class RecetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr'=>[
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50'
                ],
                'label' =>'Nom',
                'label_attr' => [
                    'class' => 'form_label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['min'=> 2, 'max'=>50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('temps', IntegerType::class,[
                'attr'=>[
                    'class'=> 'form-control',
                    'minLength'=>'1',
                    'maxLength'=>'1440'
                ],
                'label'=>'temps( en minutes )',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\Positive(),
                    new Assert\LessThan(1441)
                ]
            ])
            ->add('numberOfPerson', IntegerType::class,[
                'attr'=>[
                    'class'=> 'form-control',
                    'minLength'=>'1',
                    'maxLength'=>'50'
                ],
                'label'=>'Nombre de personnes',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\Positive(),
                    new Assert\LessThan(51)
                ]
            ])
            ->add('difficulte', RangeType::class,[
                'attr'=>[
                    'class'=> 'form-range',
                    'minLength'=>'1',
                ],
                'label'=>'Difficulté',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\Positive(),
                    new Assert\LessThan(5)
                ]
            ])
            ->add('description',TextareaType::class,[
                'attr'=>[
                    'class'=> 'form-control',
                    'minLength'=>'1',
                    'maxLength'=>'1440'
                ],
                'label'=>'Description',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'constraints'=>[
                    new Assert\NotBlank(),
                ]
            ])
            ->add('prix', MoneyType::class,[
                'attr'=>[
                    'class'=>'form-control',
                ],
                'label' => 'Prix',
                'label_attr'=>[
                    'class'=>'form_label mt-4'
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(1001)
                ]
            ])
            ->add('isFavorite', CheckboxType::class,[
                'attr'=>[
                    'class'=> 'form-check-input',
                ],
                'required'=>false,
                'label'=>'Favoris ?',
                'label_attr'=>[
                    'class'=>'form-check-label'
                ],
                'constraints'=>[
                    new Assert\NotNull(),
                ]
            ])
            ->add('ingredients', EntityType::class,[
                'class'=> Ingredient::class,
                'query_builder' => function (IngredientRepository $r) {
                    return $r->createQueryBuilder('i')
                        ->orderBy('i.name', 'ASC');
                },
                'label'=>'Les ingredients',
                'label_attr'=>[
                    'class'=>'form-label mt-4'
                ],
                'choice_label'=>'name',
                'multiple'=>true,
                'expanded'=>true
            ])
            ->add('Submit',SubmitType::class,[
                'attr' => [
                    'class'=>'btn btn-primary mt-4'
                ],
                'label' => 'Créer la recette'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }
}
