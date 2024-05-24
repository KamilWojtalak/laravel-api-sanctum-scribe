<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'user',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'email' => $this->email,

                // NOTE jest na to shortcut
                // 'emailVerifiedAt' => $this->when(
                //     $request->routeIs('users.*'),
                //     $this->email_verified_at
                // ),
                // 'updatedAt' => $this->when(
                //     $request->routeIs('users.*'),
                //     $this->updated_at
                // ),
                // 'createdAt' => $this->when(
                //     $request->routeIs('users.*'),
                //     $this->created_at
                // ),

                $this->mergeWhen(
                    $request->routeIs('users.*'),
                    [
                        'emailVerifiedAt' => $this->email_verified_at,
                        'updatedAt' => $this->updated_at,
                        'createdAt' => $this->created_at,
                    ]
                )
            ],
            'relationships' => [
                'author' => [
                    'data' => [
                        'type' => 'user',
                        'id' => $this->id,
                    ],
                    'links' => [
                        'self' => route('users.show', $this->id)
                    ]
                ]
            ],
            'includes' => TicketsResource::collection($this->whenLoaded('tickets')),
            'links' => [
                'self' => route('tickets.show', [$this])
            ]
        ];
    }
}
