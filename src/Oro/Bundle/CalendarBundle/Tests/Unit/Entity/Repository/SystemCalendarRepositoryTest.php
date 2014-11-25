<?php

namespace Oro\Bundle\CalendarBundle\Tests\Unit\Entity\Repository;

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

use Oro\Bundle\TestFrameworkBundle\Test\Doctrine\ORM\OrmTestCase;
use Oro\Bundle\TestFrameworkBundle\Test\Doctrine\ORM\Mocks\EntityManagerMock;

use Oro\Bundle\CalendarBundle\Entity\Repository\SystemCalendarRepository;

class SystemCalendarRepositoryTest extends OrmTestCase
{
    /**
     * @var EntityManagerMock
     */
    protected $em;

    protected function setUp()
    {
        $reader         = new AnnotationReader();
        $metadataDriver = new AnnotationDriver(
            $reader,
            'Oro\Bundle\CalendarBundle\Entity'
        );

        $this->em = $this->getTestEntityManager();
        $this->em->getConfiguration()->setMetadataDriverImpl($metadataDriver);
        $this->em->getConfiguration()->setEntityNamespaces(
            array(
                'OroCalendarBundle' => 'Oro\Bundle\CalendarBundle\Entity'
            )
        );
    }

    public function testGetSystemCalendarsByIdsQueryBuilder()
    {
        /** @var SystemCalendarRepository $repo */
        $repo = $this->em->getRepository('OroCalendarBundle:SystemCalendar');

        $qb = $repo->getSystemCalendarsByIdsQueryBuilder([]);

        $this->assertEquals(
            'SELECT sc'
            . ' FROM Oro\Bundle\CalendarBundle\Entity\SystemCalendar sc'
            . ' WHERE sc.public = :public AND 1 = 0',
            $qb->getQuery()->getDQL()
        );

        $qb = $repo->getSystemCalendarsByIdsQueryBuilder([1, 2]);

        $this->assertEquals(
            'SELECT sc'
            . ' FROM Oro\Bundle\CalendarBundle\Entity\SystemCalendar sc'
            . ' WHERE sc.public = :public AND sc.id IN(1, 2)',
            $qb->getQuery()->getDQL()
        );
    }

    public function testGetPublicCalendarsQueryBuilder()
    {
        /** @var SystemCalendarRepository $repo */
        $repo = $this->em->getRepository('OroCalendarBundle:SystemCalendar');

        $qb = $repo->getPublicCalendarsQueryBuilder();

        $this->assertEquals(
            'SELECT sc'
            . ' FROM Oro\Bundle\CalendarBundle\Entity\SystemCalendar sc'
            . ' WHERE sc.public = :public',
            $qb->getQuery()->getDQL()
        );
    }
}
