<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\SalesBundle\Mailer;

use Sylius\Bundle\SalesBundle\Model\OrderInterface;

/**
 * Sales mailer interface.
 *
 * @author Saša Stamenković <umpirsky@gmail.com>
 */
interface MailerInterface
{
    public function sendOrderConfirmationNotification(OrderInterface $order);
}
