<?php

namespace Mautic\UserBundle\Form\Type;

use Mautic\UserBundle\Model\UserModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserListType extends AbstractType
{
    private \Mautic\UserBundle\Model\UserModel $userModel;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'choices'           => $this->getUserChoices(),
                'expanded'          => false,
                'multiple'          => true,
                'required'          => false,
                'placeholder'       => 'mautic.core.form.chooseone',
            ]
        );
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    private function getUserChoices(): array
    {
        $choices = [];
        $users   = $this->userModel->getRepository()->getEntities(
            [
                'filter' => [
                    'force' => [
                        [
                            'column' => 'u.isPublished',
                            'expr'   => 'eq',
                            'value'  => true,
                        ],
                    ],
                ],
            ]
        );

        foreach ($users as $user) {
            $choices[$user->getName(true)] = $user->getId();
        }

        // sort by user name
        ksort($choices);

        return $choices;
    }
}
