<?php

namespace App\Permissinons\V1;

use App\Models\User;

final class Abilities
{
    public const ALL_PERMISSIONS = 'all';
    public const CREATE_TICKET = 'ticket:create';
    public const SHOW_ALL_TICKET = 'ticket:all:show';

    public static function getAbilities(User $user)
    {
        // if ($user->isAdmin())
        if (true)
        {
            return [
                static::ALL_PERMISSIONS,
            ];
        }
        // manager
        else if (false)
        {
            return [
                static::SHOW_ALL_TICKET,
            ];
        }
        else
        {
            return [
            ];
        }
    }
}
