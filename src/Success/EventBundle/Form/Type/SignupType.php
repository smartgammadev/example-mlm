<?php
namespace Success\EventBundle\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class SignupType extends AbstractType {

    private $pm;
    public function __construct($placeholderManager) {
        $this->pm = $placeholderManager;
        //$this->placeholders = $placeholders;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $phValues=$this->pm->getPlaceholdersValuesFormSession();
        
        foreach ($phValues as $phValue){
            if ($phValue['placeholder']->getPlaceholderType()->getPattern()=='user'){
                $builder->add($phValue['placeholder']->getFullPattern(),
                        'text',array('label'=>$phValue['placeholder']->getName(),'data'=>$phValue['value']));
            }
        }
        $builder->add('SignUp', 'submit');
    }
    
    public function getName()
    {
        return 'signup';
    }
}
