<?php

namespace app\modules\auth\components\permission\traits;

use app\modules\auth\components\permission\models\ModelHasRoles;
use app\modules\auth\components\permission\models\ModelHasPermissions;
use app\modules\auth\components\permission\models\Roles;
use app\modules\auth\components\permission\models\Permissions;
use Yii;

trait HasRoles
{
    use HasPermissions;

    /**
     * Assign the given role to the model.
     *
     * @param array|string|\Spatie\Permission\Contracts\Role ...$roles
     *
     * @return $this
     */
    public function assignRole(...$roles)
    {
        $roles = Roles::find()->where(['in', 'name', $roles])->all();
        if ($roles) {
            $modelId = $this->id;
            foreach ($roles as $role) {
                $modelHasRoles = (ModelHasRoles::find()->where(['model_id' => $modelId])->one() ?? new ModelHasRoles);
                $modelHasRoles->role_id = $role->id;
                $modelHasRoles->model_type = get_class($this);
                $modelHasRoles->model_id = $modelId;
                if (!$modelHasRoles->save()) {
                    throw new \app\exceptions\HttpSaveException($modelHasRoles);
                }
            }
        } else {
            throw new \yii\web\HttpException(400, "Los roles a asignar no existen! ");
        }
            
        return $this;
    }

    /**
     * Revoke the given role from the model.
     *
     * @param string|\Spatie\Permission\Contracts\Role $role
     */
    public function removeRole($role)
    {
        $this->roles()->detach($this->getStoredRole($role));

        $this->load('roles');

        $this->forgetCachedPermissions();

        return $this;
    }

    /**
     * Determine if the model has (one of) the given role(s).
     *
     * @param string|int|array|\app\models\Roles $roles
     *
     * @return bool
     */
    public function hasRole($roles): bool
    {
        if (is_string($roles) && false !== strpos($roles, '|')) {
            $roles = $this->convertPipeToArray($roles);
        }

        if (is_string($roles)) {
            return (bool) $this->getRoles(['name' => $roles])->one();
        }

        if (is_int($roles)) {
            return (bool) $this->getRoles(['id' => $roles])->one();
        }
        if ($roles instanceof Roles) {
            return (bool) $this->getRoles(['id' => $roles->id])->one();
        }

        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determine if the model has any of the given role(s).
     *
     * @param string|int|array|\app\models\Roles $roles
     *
     * @return bool
     */
    public function hasAnyRole($roles): bool
    {
        return $this->hasRole($roles);
    }


    public function getRoleNames(): Collection
    {
        return $this->roles->pluck('name');
    }

    protected function getStoredRole($role): Role
    {
        $roleClass = $this->getRoleClass();

        if (is_numeric($role)) {
            return $roleClass->findById($role, $this->getDefaultGuardName());
        }

        if (is_string($role)) {
            return $roleClass->findByName($role, $this->getDefaultGuardName());
        }

        return $role;
    }

    protected function convertPipeToArray(string $pipeString)
    {
        $pipeString = trim($pipeString);

        if (strlen($pipeString) <= 2) {
            return $pipeString;
        }

        $quoteCharacter = substr($pipeString, 0, 1);
        $endCharacter = substr($quoteCharacter, -1, 1);

        if ($quoteCharacter !== $endCharacter || !in_array($quoteCharacter, ["'", '"'])) {
            return explode('|', $pipeString);
        }

        return explode('|', trim($pipeString, $quoteCharacter));
    }

    /**
     * Join with permission relation many to many 
     * @return app\model\ModelHasRoles
     */

    public function getRoles($arrayWhere = []) {
        $relation = $this->hasMany(Roles::className(), ['id' => 'role_id'])
          ->viaTable(ModelHasRoles::tableName(), ['model_id' => 'id']);

        if (count($arrayWhere)) {
            foreach ($arrayWhere as $key => $value) {
                $relation->where("{$key} = :{$key}", [":$key" => $value]);
            }
        }

        return $relation;
    }

    /**
     * Join with permission relation many to many 
     * @return app\model\ModelHasPermissions
     */

    public function getPermissions($arrayWhere = []) {
        $relation = $this->hasMany(Permissions::className(), ['id' => 'permission_id'])
          ->viaTable(ModelHasPermissions::tableName(), ['model_id' => 'id']);

        if (count($arrayWhere)) {
            foreach ($arrayWhere as $key => $value) {
                $relation->where("{$key} = :{$key}", [":$key" => $value]);
            }
        }

        return $relation;
    }
    
}

