<?php

declare(strict_types=1);

namespace App\Observers;

use App\Enums\RoleEnum;
use App\Enums\StatusEnum;
use App\Models\User\Role;
use App\Models\User\Status;
use App\Models\User\User;
use App\Services\Cache\KeyGenerator\KeyGenerator;
use Illuminate\Support\Facades\Cache;

class UserObserver
{
    /**
     * @param User $user
     *
     * @return void
     */
    public function created(User $user): void
    {
        $this->bindDefaultRole($user);
    }

    /**
     * @param User $user
     *
     * @return void
     */
    private function bindDefaultRole(User $user): void
    {
        Role::query()
            ->create([
                'user_id' => $user->id,
                'value' => RoleEnum::DEFAULT_ROLE,
                'permissions' => RoleEnum::DEFAULT_ROLE->getDefaultPermissions(),
            ]);
    }
}
