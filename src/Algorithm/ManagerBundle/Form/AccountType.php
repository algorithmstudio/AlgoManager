<?php

namespace Algorithm\ManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AccountType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text')
            ->add('login', 'text', array('required' => false))
            ->add('password', 'text')
            ->add('url', 'text', array('required' => false))
            ->add('informations', 'textarea', array('required' => false))
            ->add('clients', 'entity', array(
           
            'class' => 'AlgorithmManagerBundle:Clients',
            'property' => 'name'
            
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Algorithm\ManagerBundle\Entity\Account'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'algorithm_managerbundle_account';
    }
}
