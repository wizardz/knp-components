<?php

namespace Knp\Component\Pager\Event\Subscriber\Paginate\Doctrine\ORM;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Knp\Component\Pager\Event\ItemsEvent;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class QuerySubscriber implements EventSubscriberInterface
{
    /**
     * @inheritdoc
     */
    public function items(ItemsEvent $event)
    {
        if ($event->target instanceof Query || $event->target instanceof QueryBuilder) {
            $event->target->setFirstResult($event->getOffset())
                ->setMaxResults($event->getLimit());
            $pager = new Paginator($event->target, true);
            $event->count = $pager->count();
            $event->items = $pager->getIterator();
            $event->stopPropagation();
        }
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            'knp_pager.items' => array('items', 0)
        );
    }
}
