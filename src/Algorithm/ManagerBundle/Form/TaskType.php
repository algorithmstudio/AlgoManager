<?php

namespace Algorithm\ManagerBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TaskType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('timeEstimate', 'text', array('required' => false))
            ->add('timeSpend', 'text', array('required' => false))
            ->add('description')
            ->add('deadLine', 'date', array('required' => false))
            ->add('user', 'entity', array(
           
                'class' => 'AlgorithmManagerBundle:User',
                'property' => 'search'
            
            ))
            ->add('project', 'entity', array(
           
                'class' => 'AlgorithmManagerBundle:Project',
                'property' => 'name'
            
            ))
            ->add('priority', 'choice', array(
                'choices' => array('Haute' => 'Haute', 'Normale' => 'Normale', 'Faible' => 'Faible')
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Algorithm\ManagerBundle\Entity\Task'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'algorithm_managerbundle_task';
    }
}
