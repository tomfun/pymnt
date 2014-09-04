<?php
namespace Tommy\Pymnt\MainBundle\Service;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Created by IntelliJ IDEA.
 * User: tomfun
 * Date: 22.08.14
 * Time: 21:33
 */
class RegistrationFormType extends AbstractType
{

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'registration';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
                'data_class' => 'Tommy\Pymnt\MainBundle\Entity\User',
                'cascade_validation' => true,
            ]
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', 'email',['error_bubbling' => true])
            ->add('plainPassword', 'password',['error_bubbling' => true])
            ->add('phone', 'text',['error_bubbling' => true])
            ->add('submit', 'submit');
    }
}