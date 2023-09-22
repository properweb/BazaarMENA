<?php

namespace Modules\User\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\User\Entities\User;
class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can update the brand.
     *
     * @param User $user
     * @return bool
     */
    public function update(User $user): bool
    {
        return $this->isOwner($user);
    }

    /**
     * Determine whether the user is brand.
     *
     * @param User $user
     * @return bool
     */
    protected function isBrand(User $user): bool
    {
        return $user->role === User::ROLE_BRAND;
    }

    /**
     * Determine whether the user created the brand.
     *
     * @param User $user
     * @return bool
     */
    protected function isOwner(User $user): bool
    {
        return $user->id;
    }
}
