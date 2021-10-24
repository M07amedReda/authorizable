<?php
namespace MohamedReda\Authorizable\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
trait Authorizable
{
    /**
     * @return mixed
     */
    public function getPermissions(): Collection
    {
        return $this->getRolePermissions()->merge($this->getUserPermissions());
    }

    /**
     * @return mixed
     */
    public function getRolePermissions(): Collection
    {
        return $this->roles->pluck('permissions')->collapse();
    }

    /**
     * @return mixed
     */
    public function getUserPermissions(): Collection
    {
        return $this->transformRolesToCollection($this->permissions ?? []);
    }

    /**
     * @param array $roles
     * @return mixed
     */
    public function hasAllRole(array $roles): bool
    {
        $permissions = $this->getPermissions()->only($roles);
        if (count($roles) !== $permissions->count()) {
            return false;
        }

        return $permissions->contains(function ($value, $role) {
            return $this->hasRole($role);
        });
    }

    /**
     * @param array $roles
     * @return mixed
     */
    public function hasAnyRole(array $roles): bool
    {
        return $this->transformRolesToCollection($roles)->contains(function ($role) {
            return $this->hasRole($role);
        });
    }

    /**
     * @param string $role
     * @return mixed
     */
    public function hasRole(string $role): bool
    {
        return $this->getPermissions()->contains(function ($value, $key) use ($role) {
            return ($key === $role || (Str::is($role, $key) || Str::is($key, $role))) && $value;
        });
    }

    /**
     * @param string $slug
     * @return mixed
     */
    public function inRole(string $slug): bool
    {
        return $this->roles()->latest()->first()->slug === $slug;
    }

    /**
     * @param $permission
     */
    public function removePermission(...$permission): bool
    {
        $permissions = $this->getUserPermissions()->except($permission);

        return (bool) $this->savePermissions($permissions);
    }

    /**
     * @param Collection $permissions
     * @return mixed
     */
    public function savePermissions(Collection $permissions): bool
    {
        return $this->fill(['permissions' => $permissions->toArray()])->save();
    }

    /**
     * @param $permission
     * @param $value
     * @return mixed
     */
    public function updateOrCreatePermission($permission, $value = true): bool
    {
        $permissions = $this->transformRolesToCollection(is_array($permission) ?: [$permission => $value]);
        $permissions = $this->getUserPermissions()->merge($permissions);

        return $this->savePermissions($permissions);
    }

    /**
     * @param $roles
     */
    protected function transformRolesToCollection($roles): Collection
    {
        return collect((array) $roles);
    }
}
