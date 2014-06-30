<?php
return [
    'doctrine' => [
        'driver' => [
            // overriding zfc-user-doctrine-orm's config
            'zfcuser_entity' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => __DIR__ . '/../src/JhUser/Entity',
            ],
 
            'orm_default' => [
                'drivers' => [
                    'JhUser\Entity' => 'zfcuser_entity',
                ],
            ],
        ],
    ],
 
    'zfcuser' => [
        // telling ZfcUser to use our own class
        'user_entity_class'         => 'JhUser\Entity\User',
        // telling ZfcUserDoctrineORM to skip the entities it defines
        'enable_default_entities'   => false,
        //enable registering
        'enable_registration'       => true,
        //enable username
        'enable_username'           => true,
    ],

    'bjyauthorize' => [
        // Using the authentication identity provider, which basically reads the roles from the auth service's identity
        'identity_provider' => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',

        'role_providers'        => [
            // using an object repository (entity repository) to load all roles into our ACL
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => [
                'object_manager'    => 'doctrine.entitymanager.orm_default',
                'role_entity_class' => 'JhUser\Entity\Role',
            ],
        ],
    ],

    //console routes
    'console' => [
        'router' => [
            'routes' => [
                'set-role' => [
                    'options'   => [
                        'route'     => 'set role <userEmail> <role>',
                        'defaults'  => [
                            'controller' => 'JhUser\Controller\Role',
                            'action'     => 'set-role'
                        ],
                    ],
                ],
            ],
        ],
    ],

    //controllers
    'controllers' => [
        'factories' => [
            'JhUser\Controller\Role' => 'JhUser\Controller\Factory\RoleControllerFactory',
        ],
    ],

    //service manager
    'service_manager' => [
        'factories' => [
            'JhUser\Repository\RoleRepository'          => 'JhUser\Repository\Factory\RoleRepositoryFactory',
            'JhUser\Repository\UserRepository'          => 'JhUser\Repository\Factory\UserRepositoryFactory',
            'JhUser\Repository\PermissionRepository'    => 'JhUser\Repository\Factory\PermissionRepositoryFactory',
        ],
        'aliases' => [
            'JhUser\ObjectManager'             => 'Doctrine\ORM\EntityManager',
        ],
    ],
];
