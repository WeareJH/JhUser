<?php

namespace JhUserTest\Entity;

use JhUser\Entity\HierarchicalRole;
use JhUser\Entity\Permission;

/**
 * Class RoleTest
 * @package JhUserTest\Entity
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class RoleTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Role
     */
    protected $role;

    /**
     * SetUp
     */
    public function setUp()
    {
        $this->role = new HierarchicalRole;
    }

    /**
     * Test the setter/getters
     *
     * @param        string $name
     * @param        mixed  $value
     *
     * @dataProvider setterGetterProvider
     */
    public function testSetterGetter($name, $value)
    {
        $getMethod = 'get' . ucfirst($name);
        $setMethod = 'set' . ucfirst($name);

        $this->assertNull($this->role->$getMethod());
        $this->role->$setMethod($value);
        $this->assertSame($value, $this->role->$getMethod());
    }

    /**
     * @return array
     */
    public function setterGetterProvider()
    {
        return [
            ['name', 'admin'],
        ];
    }

    public function testHasChildrenReturnsFalseWhenNoChildren()
    {
        $this->assertFalse($this->role->hasChildren());
    }

    public function testHasChildrenReturnsTrueWhenHasChildren()
    {
        $this->role->addChild(new HierarchicalRole());
        $this->assertTrue($this->role->hasChildren());
    }

    public function testAddPermissionToRole()
    {
        $permission = new Permission("test");

        $refObject   = new \ReflectionObject($this->role);
        $refProperty = $refObject->getProperty('permissions');
        $refProperty->setAccessible(true);

        $this->assertEmpty($refProperty->getValue($this->role));
        $this->role->addPermission($permission);
        $this->assertCount(1, $refProperty->getValue($this->role));
        $this->assertSame($permission, $refProperty->getValue($this->role)[ (string) $permission]);
    }

    public function testHasPermission()
    {
        $this->assertFalse($this->role->hasPermission('not-here'));
        $this->role->addPermission(new Permission('delete'));
        $this->assertTrue($this->role->hasPermission('delete'));
    }
}