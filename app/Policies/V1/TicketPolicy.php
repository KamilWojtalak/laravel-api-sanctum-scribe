<?php

namespace App\Policies\V1;

use App\Models\Ticket;
use App\Models\User;
use App\Permissinons\V1\Abilities;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    public function view(User $user, Ticket $ticket): bool
    {
        if ($this->isAdmin($user) || $user->tokenCan(Abilities::SHOW_ALL_TICKET))
        {
            return true;
        }

        return $user->id === $ticket->user_id;
    }

    private function isAdmin(User $user): bool
    {
        return $user->tokenCan(Abilities::ALL_PERMISSIONS);
    }
}
