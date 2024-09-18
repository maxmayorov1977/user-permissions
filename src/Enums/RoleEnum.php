<?php

declare(strict_types=1);

namespace App\Enums;

enum RoleEnum: int
{
    public const RoleEnum DEFAULT_ROLE = self::customer;

    case admin = 0;
    case editor = 1;
    case customer = 2;

    /**
     * @return PermissionEnum
     */
    public function getDefaultPermissions(): PermissionEnum
    {
        return match ($this) {
            self::admin, self::editor, self::customer => PermissionEnum::basePermissions,
        };
    }
}
