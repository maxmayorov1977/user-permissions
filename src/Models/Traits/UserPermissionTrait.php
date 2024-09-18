<?php

declare(strict_types=1);

namespace App\Models\Traits;

use App\Enums\PermissionEnum;
use Illuminate\Support\Facades\DB;

trait UserPermissionTrait
{
    private const int SETTED_BIT = 1;

    /**
     * @param array<int, PermissionEnum> $issuedPermissions
     *
     * @return void
     */
    public function issuePermission(array $issuedPermissions): void
    {
        if (empty($issuedPermissions)) {
            return;
        }

        $userPermissionList = $this->getPermissionList();

        $issued = 0;

        foreach ($issuedPermissions as $permission) {
            if (in_array($permission, $userPermissionList)) {
                continue;
            }

            $issued |= $permission->value;
        }

        $this->role->permissions |= $issued;
        $this->push();
    }

    /**
     * @param array<int, PermissionEnum> $revokedPermissions
     *
     * @return void
     */
    public function revokePermission(array $revokedPermissions = []): void
    {
        $revokedPermissions = !empty($revokedPermissions)
            ? $revokedPermissions
            : PermissionEnum::cases();

        $userPermissionList = $this->getPermissionList();

        $revoked = 0;

        foreach ($revokedPermissions as $permission) {
            if (!in_array($permission, $userPermissionList)) {
                continue;
            }

            $revoked |= $permission->value;
        }

        $this->role->permissions &= ~$revoked;
        $this->push();
    }

    /**
     * @param PermissionEnum $requiredPermission
     *
     * @return bool
     */
    public function hasPermission(PermissionEnum $requiredPermission): bool
    {
        return (bool) ($this->role->permissions & $requiredPermission->value);
    }

    /**
     * @return array<int, PermissionEnum>
     */
    public function getPermissionList(): array
    {
        $role = DB::table('users')
            ->join('roles', 'users.id', '=', 'roles.user_id')
            ->where('id', '=', $this->getAttribute())
            ->first();

        $bits = decbin($role->getArribute('permissions'));

        $result = [];

        foreach (str_split(strrev($bits)) as $index => $bit) {
            if ((int) $bit === self::SETTED_BIT) {
                $result[] = PermissionEnum::cases()[$index];
            }
        }

        return $result;
    }
}

