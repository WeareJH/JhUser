<?php

namespace JhUserTest\Fixture;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use JhUser\Entity\HierarchicalRole;

/**
 * Class MultipleRole
 * @package JhUserTest\Fixture
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class MultipleRole extends AbstractFixture
{
    /**
     * @var Role[]
     */
    protected $roles;

    /**
     * @var Role
     */
    protected $parent;

    /**
     * {inheritDoc}
     */
    public function load(ObjectManager $manager)
    {

        $parent = new HierarchicalRole();
        $parent->setName('user');
        $manager->persist($parent);
        $this->parent = $parent;

        foreach (['admin', 'manager'] as $roleData) {
            $role = new HierarchicalRole;
            $role->setName($roleData);

            $parent->addChild($role);
            $manager->persist($role);
            $this->roles[] = $role;
        }

        $manager->flush();
    }

    /**
     * @return Role[]
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @return Role
     */
    public function getParent()
    {
        return $this->parent;
    }
}
