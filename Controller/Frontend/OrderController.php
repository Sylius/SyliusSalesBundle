<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\SalesBundle\Controller\Frontend;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerAware;
use Sylius\Bundle\SalesBundle\EventDispatcher\SyliusSalesEvents;
use Sylius\Bundle\SalesBundle\EventDispatcher\Event\FilterOrderEvent;

/**
 * Frontend order controller.
 * 
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class OrderController extends ContainerAware
{
    /**
    * Places order.
    */
    public function placeAction()
    {
        $request = $this->container->get('request');
        
        $order = $this->container->get('sylius_sales.manager.order')->createOrder();
        
        $form = $this->container->get('form.factory')->create($this->container->get('sylius_sales.form.type.order'));
        $form->setData($order);
        
        $this->container->get('event_dispatcher')->dispatch(SyliusSalesEvents::ORDER_PREPARE, new FilterOrderEvent($order));
        $this->container->get('sylius_sales.processor')->prepare($order);
        
        if ('POST' == $request->getMethod()) {
            $form->bindRequest($request);
            
            if ($form->isValid()) {
                $this->container->get('event_dispatcher')->dispatch(SyliusSalesEvents::ORDER_PROCESS, new FilterOrderEvent($order));
                $this->container->get('sylius_sales.processor')->process($order);
                $this->container->get('event_dispatcher')->dispatch(SyliusSalesEvents::ORDER_PLACE, new FilterOrderEvent($order));
                $this->container->get('sylius_sales.manipulator.order')->create($order);
               
                return $this->container->get('templating')->renderResponse('SyliusSalesBundle:Frontend/Order:placed.html.' . $this->getEngine(), array(
                	'order' => $order
                ));
            }
        }
        
        return $this->container->get('templating')->renderResponse('SyliusSalesBundle:Frontend/Order:place.html.' . $this->getEngine(), array(
        	'form' => $form->createView()
        ));
    }
    
	/**
     * Confirms order.
     */
    public function confirmAction($token)
    {
        $order = $this->container->get('sylius_sales.manager.order')->findOrderBy(array('confirmationToken' => $token));
        
        if (!$order) {
            throw new NotFoundHttpException('Requested order does not exist.');
        }
        
        $this->container->get('event_dispatcher')->dispatch(SyliusSalesEvents::ORDER_CONFIRM, new FilterOrderEvent($order));
        $this->container->get('sylius_sales.manipulator.order')->confirm($order);
        
        return $this->container->get('templating')->renderResponse('SyliusSalesBundle:Frontend/Order:confirmed.html.' . $this->getEngine(), array(
        	'order' => $order
        ));
    }
    
    /**
     * Returns templating engine name.
     * 
     * @return string
     */
    protected function getEngine()
    {
        return $this->container->getParameter('sylius_sales.engine');
    }
}
