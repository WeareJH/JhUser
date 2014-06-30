<?php

namespace JhUser;

use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\DependencyIndicatorInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\EventManager\EventInterface;
use Zend\Console\Adapter\AdapterInterface as Console;

/**
 * Class Module
 * @package JhUser
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
class Module implements
    ConfigProviderInterface,
    AutoloaderProviderInterface,
    ConsoleUsageProviderInterface,
    DependencyIndicatorInterface
{

    /**
     * @param EventInterface $e
     */
    public function onBootstrap(EventInterface $e)
    {
        $application    = $e->getTarget();
        $events         = $application->getEventManager()->getSharedManager();

        //add roles to users created via HybridAuth
        $events->attach(
            'ScnSocialAuth\Authentication\Adapter\HybridAuth',
            'registerViaProvider',
            [$this, 'onRegister']
        );

        //add roles to users created via ZfcUser
        $events->attach('ZfcUser\Service\User', 'register', [$this, 'onRegister']);
    }

    /**
     * @param EventInterface $e
     */
    public function onRegister(EventInterface $e)
    {
        $application    = $e->getTarget();
        $sm             = $application->getServiceManager();
        $entityManager  = $sm->get('JhUser\ObjectManager');

        $user       = $e->getParam('user');
        //TODO: Pull default role from config
        $userRole   = $sm->get('JhUser\Repository\RoleRepository')->findByName('user');

        $user->addRole($userRole);
        $entityManager->flush();
    }
    
    /**
     * {@inheritDoc}
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }
 
    /**
     * {@inheritDoc}
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/../../src/' . __NAMESPACE__,
                ],
            ],
        ];
    }
    
    /**
     * {@inheritDoc}
     */
    public function getModuleDependencies()
    {
        return [
            'DoctrineModule',
            'DoctrineORMModule',
            'ZfcUser',
            'ZfcUserDoctrineORM',
            'ScnSocialAuthDoctrineORM'
        ];
    }


    /**
     * @param Console $console
     * @return array|null|string
     */
    public function getConsoleUsage(Console $console)
    {
        return [
            'set role <userEmail> <role>'   => 'Set a user\'s role',
        ];
    }
}
