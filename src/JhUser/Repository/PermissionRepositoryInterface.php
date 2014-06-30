<?php

namespace JhUser\Repository;

/**
 * Interface PermissionRepositoryInterface
 * @package JhUser\Repository
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
interface PermissionRepositoryInterface
{
    /**
     * @param string $name
     * @return \Rbac\Role\RoleInterface|null
     */
    public function findByName($name);

    /**
     * @param array $criteria
     * @return \Rbac\Role\RoleInterface|null
     */
    public function findOneBy(array $criteria);
}
