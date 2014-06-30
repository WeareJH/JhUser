<?php

namespace JhUserTest\Entity;

use JhUser\Entity\Permission;

/**
 * Class PermissionTest
 * @package JhUserTest\Entity
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class PermissionTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Permission
     */
    protected $permission;

    /**
     * SetUp
     */
    public function setUp()
    {
        $this->permission = new Permission('permission');
    }

    /**
     * @param Permission $permission
     * @param $id
     */
    public function setId(Permission $permission, $id)
    {
        $reflector = new \ReflectionClass($permission);
        $property  = $reflector->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($permission, $id);
    }

    public function testId()
    {
        $this->assertNull($this->permission->getId());
        $this->setId($this->permission, 1);
        $this->assertEquals(1, $this->permission->getId());
    }

    public function testToString()
    {
        $this->assertSame('permission', $this->permission->__toString());
    }
}