<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username')
            //! here we can hardcode all the roles but it wont be updated dynamically when we add or delete more role.
            //! for example when we add new role in app using CRUD then this roles here wont be updated. So to solve this we will dynamically show the list of roles in UserController edit func. 
            // ->add('roles', ChoiceType::class, [
            //     'choices' => [
            //         'ROLE_ADMIN' => 'ROLE_ADMIN',
            //         'ROLE_ASSISTANT' => 'ROLE_ASSISTANT',
            //         'ROLE_DEPOT' => 'ROLE_DEPOT',
            //         'ROLE_CAISSE' => 'ROLE_CAISSE',
            //         'ROLE_USER' => 'ROLE_USER',
            //     ],
            //     'multiple'=>true,
            //     'expanded'=>true,
            // ])
            ->add('plainPassword', PasswordType::class, [
                'attr' => ['class' => '', 'autocomplete' => 'off', 'placeholder' => 'leave empty for old password'],
                'mapped'=>false,
                'required'=>false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
