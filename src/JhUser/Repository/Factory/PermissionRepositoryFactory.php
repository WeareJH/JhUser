<?php

namespace JhUser\Repository\Factory;

use JhUser\Repository\PermissionRepository;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class PermissionRepositoryFactory
 * @package JhUser\Repository\Factory
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class PermissionRepositoryFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return RoleRepository
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new PermissionRepository(
            $serviceLocator->get('JhUser\ObjectManager')->getRepository('JhUser\Entity\Permission')
        );
    }
}
