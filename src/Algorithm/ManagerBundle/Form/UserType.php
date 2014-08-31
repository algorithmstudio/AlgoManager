<?php

namespace Algorithm\ManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname')
            ->add('name')
            ->add('email')
            ->add('password')
            ->add('roles', 'collection', array(
                'type'   => 'choice',
                'label'  => 'Profil d\'accès',
                'required' => false,
                'options'  => array(
                    'label' => '',
                    'choices'  => array(
                        'ROLE_ADMIN'    => 'Administrateur',
                        'ROLE_VIEWER'   => 'Consultant Clients',
                        'ROLE_PROJECT'   => 'Accès Projets'
                    ),
                ),
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Algorithm\ManagerBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'algorithm_managerbundle_user';
    }
}
