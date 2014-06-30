<?php

namespace JhUserTest\Repository;

use JhUserTest\Util\ServiceManagerFactory;
use JhUserTest\Fixture\SingleRole;
use JhUserTest\Fixture\MultipleRole;

/**
 * Class RoleRepositoryTest
 * @package JhUserTest\Repository
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class RoleRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Doctrine\Common\DataFixtures\Executor\AbstractExecutor
     */
    protected $fixtureExectutor;

    /**
     * @var \JhUser\Repository\RoleRepository
     */
    protected $repository;

    public function setUp()
    {
        $sm = ServiceManagerFactory::getServiceManager();
        $this->repository = $sm->get('JhUser\Repository\RoleRepository');
        $this->fixtureExectutor = $sm->get('Doctrine\Common\DataFixtures\Executor\AbstractExecutor');
        $this->assertInstanceOf('JhUser\Repository\RoleRepository', $this->repository);
    }

    public function testGetAllRoles()
    {
        $role = new SingleRole();
        $this->fixtureExectutor->execute([$role]);

        $this->assertCount(1, $this->repository->findAll());
    }

    public function testGetAllRolesWithPagination()
    {
        $role = new SingleRole();
        $this->fixtureExectutor->execute([$role]);

        $this->assertCount(1, $this->repository->findAll(true));
    }

    public function testFindByRoleNameReturnsNullIfNotExists()
    {
        $this->assertNull($this->repository->findByName("super-admin"));
    }

    public function testFindByRoleNameReturnsRoleIfExists()
    {
        $role = new SingleRole();
        $this->fixtureExectutor->execute([$role]);
        $result = $this->repository->findByName($role->getRole()->getName());
        $this->assertInstanceOf('JhUser\Entity\HierarchicalRole', $result);
        $this->assertEquals($role->getRole()->getChildren()->toArray(), $result->getChildren()->toArray());
        $this->assertSame($role->getRole()->getId(), $result->getId());
        $this->assertSame($role->getRole()->getName(), $result->getName());
    }

    public function testFindOneByReturnsNullIfNotExists()
    {
        $this->assertNull($this->repository->findOneBy(["name" => "super-admin"]));
    }

    public function testFindOneByReturnsRoleIfExists()
    {
        $role = new SingleRole();
        $this->fixtureExectutor->execute([$role]);
        $result = $this->repository->findOneBy(["name" => "admin"]);
        $this->assertInstanceOf('JhUser\Entity\HierarchicalRole', $result);
        $this->assertEquals($role->getRole()->getChildren()->toArray(), $result->getChildren()->toArray());
        $this->assertSame($role->getRole()->getId(), $result->getId());
        $this->assertSame($role->getRole()->getName(), $result->getName());
    }

    public function testFindByReturnsEmptyIfNonExist()
    {
        $this->assertEmpty($this->repository->findBy(['name' => 'admin']));
    }

    public function testFindByReturnsCollectionIfExist()
    {
        $this->assertEmpty($this->repository->findBy(['name' => 'user']));

        $roles = new MultipleRole();
        $this->fixtureExectutor->execute([$roles]);
        $result = $this->repository->findBy(["name" => "manager"]);
        $this->assertSame(1, count($result));
    }

    public function testFindById()
    {
        $role = new SingleRole();
        $this->fixtureExectutor->execute([$role]);
        $result = $this->repository->find($role->getRole()->getId());
        $this->assertInstanceOf('JhUser\Entity\HierarchicalRole', $result);
        $this->assertEquals($role->getRole()->getChildren()->toArray(), $result->getChildren()->toArray());
        $this->assertSame($role->getRole()->getId(), $result->getId());
        $this->assertSame($role->getRole()->getName(), $result->getName());
    }

    public function testGetClass()
    {
        $this->assertSame('JhUser\Entity\HierarchicalRole', $this->repository->getClassName());
    }
}
