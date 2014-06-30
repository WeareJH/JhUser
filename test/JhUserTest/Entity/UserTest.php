<?php

namespace JhUserTest\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use JhUser\Entity\HierarchicalRole;
use JhUser\Entity\User;

/**
 * Class UserTest
 * @package JhUserTest\Entity
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class UserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var User
     */
    protected $user;

    /**
     * SetUp
     */
    public function setUp()
    {
        $this->user = new User;
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

        $this->assertNull($this->user->$getMethod());
        $this->user->$setMethod($value);
        $this->assertSame($value, $this->user->$getMethod());
    }

    /**
     * @return array
     */
    public function setterGetterProvider()
    {
        return [
            ['id'          , 1],
            ['email'       , 'aydin@hotmail.co.uk'],
            ['username'    , 'aydin'],
            ['username'    , 'aydin'],
            ['displayName' , 'Aydin'],
            ['state'       , null],
            ['createdAt'   , new \DateTime],
            ['createdAt'   , new \DateTime],
            ['password'    , 'password'],
        ];
    }

    public function testAddRole()
    {
        $user = new User;

        $role1 = new HierarchicalRole;
        $role2 = new HierarchicalRole;

        $user->addRole($role1);
        $this->assertContains($role1, $user->getRoles());

        $user->addRole($role2);
        $this->assertContains($role1, $user->getRoles());
        $this->assertContains($role2, $user->getRoles());
    }

    public function testSetRoles()
    {
        $user = new User;

        $roles = new ArrayCollection([
            new HierarchicalRole(),
            new HierarchicalRole(),
        ]);

        $user->setRoles($roles);
        $this->assertSame($roles->toArray(), $user->getRoles());
    }

    public function testSetRolesOverwritesExistingRoles()
    {
        $user = new User;
        $user->addRole(new HierarchicalRole());

        $roles = new ArrayCollection([
            new HierarchicalRole(),
            new HierarchicalRole(),
        ]);

        $user->setRoles($roles);
        $this->assertSame($roles->toArray(), $user->getRoles());
    }

    public function testPrePersistCreatedAtDateInstanceOfDateTime()
    {
        $user = new User;
        $this->assertNull($user->getCreatedAt());
        $user->setCreatedAtDate();

        $this->assertInstanceOf('DateTime', $user->getCreatedAt());
    }

    public function testJsonSerializeUser()
    {
        $user = new User;
        $user->setId(1)
            ->setDisplayName("Aydin Hassan")
            ->setState(null)
            ->setEmail("aydin@hotmail.co.uk");

        $expected = [
            'id'    => 1,
            'name'  => 'Aydin Hassan',
            'state' => null,
            'email' => 'aydin@hotmail.co.uk'
        ];

        $this->assertEquals($expected, $user->jsonSerialize());
    }
}
