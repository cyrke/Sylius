<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\CoreBundle\EventListener;

use PHPSpec2\ObjectBehavior;

/**
 * Order shipping listener spec.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class OrderShippingListener extends ObjectBehavior
{
    /**
     * @param Sylius\Bundle\CoreBundle\OrderProcessing\ShippingChargesProcessorInterface $shippingProcessor
     */
    function let($shippingProcessor)
    {
        $this->beConstructedWith($shippingProcessor);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\CoreBundle\EventListener\OrderShippingListener');
    }

    /**
     * @param Symfony\Component\EventDispatcher\GenericEvent $event
     * @param \stdClass                                      $invalidSubject
     */
    function it_throws_exception_if_event_has_non_order_subject($event, $invalidSubject)
    {
        $event->getSubject()->willReturn($invalidSubject);

        $this
            ->shouldThrow('InvalidArgumentException')
            ->duringProcessOrderShippingCharges($event)
        ;
    }

    /**
     * @param Symfony\Component\EventDispatcher\GenericEvent $event
     * @param Sylius\Bundle\CoreBundle\Model\OrderInterface  $order
     */
    function it_calls_shipping_processor_on_order($shippingProcessor, $event, $order)
    {
        $event->getSubject()->willReturn($order);
        $shippingProcessor->applyShippingCharges($order)->shouldBeCalled();

        $this->processOrderShippingCharges($event);
    }
}
