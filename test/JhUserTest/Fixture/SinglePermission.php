<?php

namespace JhUserTest\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use JhUser\Entity\Permission;

/**
 * Class SinglePermission
 * @package JhUserTest\Fixture
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class SinglePermission extends AbstractFixture
{
    /**
     * @var Permission
     */
    protected $permission;

    /**
     * {inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->permission = new Permission('delete');
        $manager->persist($this->permission);
        $manager->flush();
    }

    /**
     * @return Permission
     */
    public function getPermission()
    {
        return $this->permission;
    }
} 