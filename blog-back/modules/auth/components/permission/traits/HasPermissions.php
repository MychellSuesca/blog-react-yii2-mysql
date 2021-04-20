<?php

namespace app\modules\auth\components\permission\traits;

use app\modules\auth\components\permission\models\Permissions;
use app\modules\auth\components\permission\models\RoleHasPermissions;
use Yii;


trait HasPermissions
{
    /**
     * Determine if the model may perform the given permission.
     *
     * @param string|int|\Spatie\Permission\Contracts\Permission $permission
     * @param string|null $guardName
     *
     * @return bool
     * @throws PermissionDoesNotExist
     */
    public function hasPermissionTo($permissionName, $guardName = 'web'): bool
    {
        if ($this->isAdmin()) {
            return true;
        }

        $permissionClass = new Permissions;
        $permission = $permissionName;
        $guardNameVar = 'guard_name';

        if (is_string($permission)) {
            $permission = $permissionClass->find()->where([ 'name' => $permission, $guardNameVar => $guardName ] )->one();
        }

        if (is_int($permission)) {
            $permission = $permissionClass->find()->where( [ 'id' => intval($permission), $guardNameVar => $guardName ] )->one();
        }


        if (!$permission instanceof Permissions) {
            return true;
        }

        return $this->hasDirectPermission($permission) || $this->hasPermissionViaRole($permission);
    }

    /**
     * @deprecated since 2.35.0
     * @alias of hasPermissionTo()
     */
    public function hasUncachedPermissionTo($permission, $guardName = null): bool
    {
        return $this->hasPermissionTo($permission, $guardName);
    }

    /**
     * An alias to hasPermissionTo(), but avoids throwing an exception.
     *
     * @param string|int|\Spatie\Permission\Contracts\Permission $permission
     * @param string|null $guardName
     *
     * @return bool
     */
    public function checkPermissionTo($permission, $guardName = null): bool
    {
        try {
            return $this->hasPermissionTo($permission, $guardName);
        } catch (PermissionDoesNotExist $e) {
            return false;
        }
    }

    /**
     * Determine if the model has any of the given permissions.
     *
     * @param array ...$permissions
     *
     * @return bool
     * @throws \Exception
     */
    public function hasAnyPermission(...$permissions): bool
    {
        if (is_array($permissions[0])) {
            $permissions = $permissions[0];
        }

        foreach ($permissions as $permission) {
            if ($this->checkPermissionTo($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if the model has all of the given permissions.
     *
     * @param array ...$permissions
     *
     * @return bool
     * @throws \Exception
     */
    public function hasAllPermissions(...$permissions): bool
    {
        if (is_array($permissions[0])) {
            $permissions = $permissions[0];
        }

        foreach ($permissions as $permission) {
            if (! $this->hasPermissionTo($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Determine if the model has, via roles, the given permission.
     *
     * @param \Spatie\Permission\Contracts\Permission $permission
     *
     * @return bool
     */
    protected function hasPermissionViaRole(Permissions $permission): bool
    {
        return $this->hasRole($permission->getRoles()->all());
    }

    /**
     * Determine if the model has the given permission.
     *
     * @param string|int|\Spatie\Permission\Contracts\Permission $permission
     *
     * @return bool
     * @throws PermissionDoesNotExist
     */
    public function hasDirectPermission($permission): bool
    {
        if (! $permission instanceof Permissions) {
            return false;
        }

        if (is_string($permission)) {
            $res = [ 'name' => $permission ];
        }

        if (is_int($permission)) {
            $res = [ 'id' => $permission ];
        }

        $res = (isset($res) ? $res : [ 'id' => $permission->id ]);

        return (bool) $this->getPermissions($res)->one();
    }

    /**
     * Return all the permissions the model has via roles.
     */
    public function getPermissionsViaRoles()
    {
        $permissions = [];
        foreach ($this->roles as $role) {
            if ($role->permissions) {
                $permissions += $role->permissions;
            }
        }
        return $this->getPermissionName($permissions);
    }

    /**
     * Return all the permissions the model has, both directly and via roles.
     *
     * @throws \Exception
     */
    public function getAllPermissions()
    {
        $permissions = $this->getPermissionName($this->permissions);

        if ($this->roles) {
            $permissions = array_merge($permissions, $this->getPermissionsViaRoles());
        }

        return $permissions;
    }

    public function getPermissionName($permissions)
    {
        $names = [];
        foreach ($permissions as $permission) {
            $names[] = [
                "guard_name" => $permission->guard_name,
                "name" => $permission->name,
            ];
        }
        return $names;
    }

    /**
     * Grant the given permission(s) to a role.
     *
     * @param string|array|\Spatie\Permission\Contracts\Permission|\Illuminate\Support\Collection $permissions
     *
     * @return $this
     */
    public function givePermissionTo(...$permissions)
    {
        
        $criteria = new \CDbCriteria();
        $criteria->addInCondition("name", $permissions);

        $permissions = Permissions::model()->findAll($criteria);
        if ($permissions) {
            $modelId = $this->id;
            foreach ($permissions as $permission) {
                $roleHasRoles = new RoleHasPermissions;
                $roleHasRoles->role_id = $modelId;
                $roleHasRoles->permission_id = $permission->id;
                if (!$roleHasRoles->save()) {
                    throw new \app\exceptions\HttpSaveException($roleHasRoles);
                }
            }
        } else {
            throw new \yii\web\HttpException(400, "Los Permisos a asignar no existen!");
        }

        return $this;
    }

    /**
     * Remove all current permissions and set the given ones.
     *
     * @param string|array|\Spatie\Permission\Contracts\Permission|\Illuminate\Support\Collection $permissions
     *
     * @return $this
     */
    public function syncPermissions(...$permissions)
    {
        $this->permissions()->detach();

        return $this->givePermissionTo($permissions);
    }

    /**
     * Revoke the given permission.
     *
     * @param \Spatie\Permission\Contracts\Permission|\Spatie\Permission\Contracts\Permission[]|string|string[] $permission
     *
     * @return $this
     */
    public function revokePermissionTo($permission)
    {
        $this->permissions()->detach($this->getStoredPermission($permission));

        $this->forgetCachedPermissions();

        $this->load('permissions');

        return $this;
    }

    public function getPermissionNames(): Collection
    {
        return $this->permissions->pluck('name');
    }

    /**
     * @param string|array|\Spatie\Permission\Contracts\Permission|\Illuminate\Support\Collection $permissions
     *
     * @return \Spatie\Permission\Contracts\Permission|\Spatie\Permission\Contracts\Permission[]|\Illuminate\Support\Collection
     */
    protected function getStoredPermission($permissions)
    {
        $permissionClass = $this->getPermissionClass();

        if (is_numeric($permissions)) {
            return $permissionClass->findById($permissions, $this->getDefaultGuardName());
        }

        if (is_string($permissions)) {
            return $permissionClass->findByName($permissions, $this->getDefaultGuardName());
        }

        if (is_array($permissions)) {
            return $permissionClass
                ->whereIn('name', $permissions)
                ->whereIn('guard_name', $this->getGuardNames())
                ->get();
        }

        return $permissions;
    }

    /**
     * @param \Spatie\Permission\Contracts\Permission|\Spatie\Permission\Contracts\Role $roleOrPermission
     *
     * @throws \Spatie\Permission\Exceptions\GuardDoesNotMatch
     */
    protected function ensureModelSharesGuard($roleOrPermission)
    {
        if (! $this->getGuardNames()->contains($roleOrPermission->guard_name)) {
            throw GuardDoesNotMatch::create($roleOrPermission->guard_name, $this->getGuardNames());
        }
    }

    protected function getGuardNames(): Collection
    {
        return Guard::getNames($this);
    }

    protected function getDefaultGuardName(): string
    {
        return Guard::getDefaultName($this);
    }

    /**
     * Forget the cached permissions.
     */
    public function forgetCachedPermissions()
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}

