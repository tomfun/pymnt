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
class ItemFormType extends AbstractType
{

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'item';
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
                'data_class' => 'Tommy\Pymnt\MainBundle\Entity\Item',
                'cascade_validation' => true,
            ]
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type', 'entity', ['class' => 'Tommy\Pymnt\MainBundle\Entity\ItemType', 'property' => 'class'])
            ->add('caption', 'text')
            ->add('price', 'number')
            ->add('currency', 'text')
            //->add('closedAt', 'checkbox', ['required' => false])
            ->add('submit', 'submit');
    }
}