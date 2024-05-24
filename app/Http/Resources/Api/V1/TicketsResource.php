<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketsResource extends JsonResource
{
    // NOTE tutaj zmieniasz tego key 'data' w json
    // public static $wrap = 'custom-key-name';

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'ticket',
            'id' => $this->id,
            'attributes' => [
                'title' => $this->title,
                // Tylko kiedy details page
                'description' => $this->when(
                    $request->routeIs('tickets.show'),
                    $this->description
                ),
                'status' => $this->status,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
            'relationships' => [
                'author' => [
                    'data' => [
                        'type' => 'user',
                        'id' => $this->user_id,
                    ],
                    'links' => [
                        'self' => route('users.show', $this->user_id)
                    ]
                ]
            ],
            // Only when loaded
            'includes' => new UsersResource($this->whenLoaded('user')),
            'links' => [
                'self' => route('tickets.show', [$this])
            ]
        ];
    }
}
