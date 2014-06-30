<?php

namespace JhUserTest\Repository;

use JhUserTest\Util\ServiceManagerFactory;
use JhUserTest\Fixture\SinglePermission;

/**
 * Class PermissionRepositoryTest
 * @package JhUserTest\Repository
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class PermissionRepositoryTest extends \PHPUnit_Framework_TestCase
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
        $this->repository = $sm->get('JhUser\Repository\PermissionRepository');
        $this->fixtureExectutor = $sm->get('Doctrine\Common\DataFixtures\Executor\AbstractExecutor');
        $this->assertInstanceOf('JhUser\Repository\PermissionRepository', $this->repository);
    }

    public function testGetAllPermissions()
    {
        $permission = new SinglePermission();
        $this->fixtureExectutor->execute([$permission]);

        $this->assertCount(1, $this->repository->findAll());
    }

    public function testFindByPermissionNameReturnsNullIfNotExists()
    {
        $this->assertNull($this->repository->findByName("delete"));
    }

    public function testFindByPermissionNameReturnsPermissionIfExists()
    {
        $permission = new SinglePermission();
        $this->fixtureExectutor->execute([$permission]);
        $result = $this->repository->findByName((string) $permission->getPermission());
        $this->assertInstanceOf('JhUser\Entity\Permission', $result);
        $this->assertSame($permission->getPermission()->getId(), $result->getId());
        $this->assertSame((string) $permission->getPermission(), (string) $result);
    }

    public function testFindOneByReturnsNullIfNotExists()
    {
        $this->assertNull($this->repository->findOneBy(["name" => "super-admin"]));
    }

    public function testFindOneByReturnsPermissionIfExists()
    {
        $permission = new SinglePermission();
        $this->fixtureExectutor->execute([$permission]);
        $result = $this->repository->findOneBy(["name" => "delete"]);
        $this->assertInstanceOf('JhUser\Entity\Permission', $result);
        $this->assertSame($permission->getPermission()->getId(), $result->getId());
        $this->assertSame((string) $permission->getPermission(), (string) $result);
    }

    public function testFindByReturnsEmptyIfNonExist()
    {
        $this->assertEmpty($this->repository->findBy(['name' => 'admin']));
    }

    public function testFindByReturnsCollectionIfExist()
    {
        $this->assertEmpty($this->repository->findBy(['name' => 'user']));

        $permission = new SinglePermission();
        $this->fixtureExectutor->execute([$permission]);
        $result = $this->repository->findBy(["name" => (string) $permission->getPermission()]);
        $this->assertSame(1, count($result));
    }

    public function testFindById()
    {
        $permission = new SinglePermission();
        $this->fixtureExectutor->execute([$permission]);
        $result = $this->repository->find($permission->getPermission()->getId());
        $this->assertInstanceOf('JhUser\Entity\Permission', $result);
        $this->assertSame($permission->getPermission()->getId(), $result->getId());
        $this->assertSame((string) $permission->getPermission(), (string) $result);
    }

    public function testGetClass()
    {
        $this->assertSame('JhUser\Entity\Permission', $this->repository->getClassName());
    }
}
