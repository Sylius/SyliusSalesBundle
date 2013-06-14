<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\SalesBundle\EventListener;

use Symfony\Component\EventDispatcher\GenericEvent;
use Sylius\Bundle\SalesBundle\Mailer\MailerInterface;

/**
 * Order confirmation listener.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 * @author Saša Stamenković <umpirsky@gmail.com>
 */
class OrderConfirmationListener
{
    /**
     * Sends email confirmation notification.
     *
     * @var MailerInterface
     */
    protected $mailer;

    /**
     * Are order confirmations enabled?
     *
     * @var Boolean
     */
    protected $enabled;

    /**
     * Constructor.
     *
     * @param MailerInterface $mailer
     * @param Boolean         $enabled
     */
    public function __construct(MailerInterface $mailer, $enabled)
    {
        $this->mailer = $mailer;
        $this->enabled = $enabled;
    }

    /**
     *Sends order confirmation email to buyer if confirmations are enabled.
     *
     * @param GenericEvent $event
     */
    public function sendOrderConfirmation(GenericEvent $event)
    {
        $order = $event->getSubject();

        if ($this->enabled) {
            $order->setConfirmationToken(md5(uniqid(mt_rand(), true)));
            $this->mailer->sendOrderConfirmationNotification($order);
        } else {
            $order->setConfirmed(true);
        }
    }
}
