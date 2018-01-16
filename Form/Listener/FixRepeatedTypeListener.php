<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\UserBundle\Form\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class FixRepeatedTypeListener
 *
 * TODO see https://github.com/symfony/symfony/issues/21242
 */
class FixRepeatedTypeListener implements EventSubscriberInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SUBMIT => 'preSubmit',
        ];
    }

    public function preSubmit(FormEvent $event)
    {
        $config = $event->getForm()->getConfig();

        if (false === is_array($event->getData())) {
            $event->setData([
                $config->getOption('first_name') => $event->getData(),
                $config->getOption('second_name') => null,
            ]);
        }
    }
}
