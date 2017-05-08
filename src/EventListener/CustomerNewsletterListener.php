<?php

namespace BitBag\MailChimpPlugin\EventListener;

use BitBag\MailChimpPlugin\Handler\NewsletterSubscriptionHandler;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Symfony\Component\EventDispatcher\GenericEvent;

class CustomerNewsletterListener
{
    /**
     * @var NewsletterSubscriptionHandler
     */
    private $newsletterSubscriptionHandler;

    /**
     * @param NewsletterSubscriptionHandler $newsletterSubscriptionHandler
     */
    public function __construct(NewsletterSubscriptionHandler $newsletterSubscriptionHandler)
    {
        $this->newsletterSubscriptionHandler = $newsletterSubscriptionHandler;
    }

    /**
     * @param GenericEvent $event
     */
    public function customerCreateEvent(GenericEvent $event)
    {
        $customer = $event->getSubject();

        if (!$customer instanceof CustomerInterface) {
            throw new UnexpectedTypeException(
                $customer,
                CustomerInterface::class
            );
        }

        $this->subscribe($customer);
    }


    /**
     * @param GenericEvent $event
     */
    public function customerUpdateEvent(GenericEvent $event)
    {
        $this->customerCreateEvent($event);
    }

    private function subscribe(CustomerInterface $customer)
    {
        if ($customer->isSubscribedToNewsletter() === true) {

            $this->newsletterSubscriptionHandler->subscribe($customer->getEmail());
        }
    }
}