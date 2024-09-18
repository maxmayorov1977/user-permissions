<?php

declare(strict_types=1);

namespace App\Enums;

enum PermissionEnum: int
{
    case basePermissions = 1 << 0;
    case viewResources = 1 << 1;
    case createResources = 1 << 2;
    case editResources = 1 << 3;
    case deleteResources = 1 << 4;
}

