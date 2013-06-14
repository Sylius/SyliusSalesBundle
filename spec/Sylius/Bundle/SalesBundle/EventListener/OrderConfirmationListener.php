<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\SalesBundle\EventListener;

use PHPSpec2\ObjectBehavior;

/**
 * Order confirmation listener spec.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 * @author Saša Stamenković <umpirsky@gmail.com>
 */
class OrderConfirmationListener extends ObjectBehavior
{
    /**
     * @param Sylius\Bundle\SalesBundle\Mailer\MailerInterface $mailer
     */
    public function let($mailer)
    {
        $this->beConstructedWith($mailer, true);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\SalesBundle\EventListener\OrderConfirmationListener');
    }

    /**
     * @param Sylius\Bundle\SalesBundle\Model\OrderInterface $order
     * @param Symfony\Component\EventDispatcher\GenericEvent $event
     */
    function it_sends_confirmation_email_if_confirmation_is_enabled($mailer, $event, $order)
    {
        $event->getSubject()->willReturn($order);
        $mailer->sendOrderConfirmationNotification($order)->shouldBeCalled();
        $order->setConfirmed()->shouldNotBeCalled();

        $this->sendOrderConfirmation($event);
    }
}
