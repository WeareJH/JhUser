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

    'zfc_rbac' => [
        'redirect_strategy' => [
            //don't redirect if already logged in
            'redirect_when_connected'        => false,
            'redirect_to_route_connected'    => 'home',
            'redirect_to_route_disconnected' => 'zfcuser/login',
            'append_previous_uri'            => true,
            'previous_uri_query_key'         => 'redirectTo',
        ],

        'guards' => [
            'ZfcRbac\Guard\RouteGuard' => [
                //ZFCUser Routes
                'zfcuser/login'                 => ['guest'],
                'zfcuser/register'              => ['guest'],
                'zfcuser*'                      => ['user'],

                //home
                'home'                          => ['user'],
            ]
        ],

        'role_provider' => [
            'ZfcRbac\Role\ObjectRepositoryRoleProvider' => [
                'object_manager'     => 'doctrine.entitymanager.orm_default',
                'class_name'         => 'JhUser\Entity\HierarchicalRole',
                'role_name_property' => 'name', // Name to show
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
            'JhUser\ObjectManager'                      => 'Doctrine\ORM\EntityManager',
            'Zend\Authentication\AuthenticationService' => 'zfcuser_auth_service'
        ],
    ],
];
