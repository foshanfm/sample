<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Status;

class StatusPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function destroy(User $user, Status $status)
    {
        //微博删除动作验证
        return $user->id === $status->user_id;
    }
}
