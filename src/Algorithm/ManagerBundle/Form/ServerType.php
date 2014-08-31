<?php

namespace Algorithm\ManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ServerType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('user', 'text', array('required' => false ))
            ->add('password', 'text')
            ->add('ip', 'text', array('required' => false ))
            ->add('hebergeur', 'text', array('required' => false ))
            ->add('memoire', 'text', array('required' => false ))
            ->add('bandePassante', 'text', array('required' => false ))
            ->add('processeur', 'text', array('required' => false ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Algorithm\ManagerBundle\Entity\Server'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'algorithm_managerbundle_server';
    }
}
