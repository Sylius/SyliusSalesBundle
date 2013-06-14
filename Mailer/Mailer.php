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

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use Sylius\Bundle\SalesBundle\Model\OrderInterface;
use Swift_Mailer;
use Swift_Message;

class Mailer implements MailerInterface
{
    protected $mailer;
    protected $router;
    protected $templating;
    protected $parameters;

    public function __construct($mailer, RouterInterface $router, EngineInterface $templating, array $parameters)
    {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->templating = $templating;
        $this->parameters = $parameters;
    }

    public function sendOrderConfirmationNotification(OrderInterface $order)
    {
        $rendered = $this->templating->render($this->parameters['confirmation.template'], array(
            'order'           => $order,
            'confirmationUrl' => $this->router->generate('sylius_order_confirm', array('token' => $order->getConfirmationToken()), true)
        ));

        $renderedLines = explode("\n", trim($rendered));
        $subject = $renderedLines[0];
        $body = implode("\n", array_slice($renderedLines, 1));

        $message = Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom($this->parameters['confirmation.from_email']['address'], $this->parameters['confirmation.from_email']['sender_name'])
            ->setTo($order->getUser()->getEmail())
            ->setBody($body);

        $this->mailer->send($message);
    }
}
