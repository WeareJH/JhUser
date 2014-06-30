<?php

namespace JhUser\Repository;

/**
 * Interface RoleRepositoryInterface
 * @package JhUser\Repository
 * @author Aydin Hassan <aydin@hotmail.co.uk>
 */
interface RoleRepositoryInterface
{
    /**
     * @param string $roleName
     * @return \Rbac\Role\RoleInterface|null
     */
    public function findByName($roleName);

    /**
     * @param array $criteria
     * @return \Rbac\Role\RoleInterface|null
     */
    public function findOneBy(array $criteria);
}
