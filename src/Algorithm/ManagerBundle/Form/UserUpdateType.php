<?php

namespace Algorithm\ManagerBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;

class UserUpdateType extends UserType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {        
        parent::buildForm($builder, $options);
        
        $builder
            ->remove('password')
        ;
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return 'algorithm_managerbundle_user_update';
    }
}
