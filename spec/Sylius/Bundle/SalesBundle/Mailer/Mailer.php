<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\SalesBundle\Mailer;

use PHPSpec2\ObjectBehavior;

class Mailer extends ObjectBehavior
{
    /**
     * @param Swift_Mailer                                              $mailer
     * @param Symfony\Component\Routing\RouterInterface                 $router
     * @param Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     */
    public function let($mailer, $router, $templating)
    {
        $this->beConstructedWith(
            $mailer,
            $router,
            $templating,
            array(
                'confirmation.template'   => 'SyliusSalesBundle:Confirmation:email.txt.twig',
                'confirmation.from_email' => array(
                    'address'     => 'umpirsky@gmail.com',
                    'sender_name' => 'Saša'
                ),
            )
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\SalesBundle\Mailer\Mailer');
    }

    function it_implements_mailer_interface()
    {
        $this->shouldImplement('Sylius\Bundle\SalesBundle\Mailer\MailerInterface');
    }
}
