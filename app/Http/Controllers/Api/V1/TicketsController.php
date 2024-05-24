<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\TicketFilter;
use App\Http\Requests\Api\V1\ReplaceTicketRequest;
use App\Http\Requests\Api\V1\StoreTicketRequest;
use App\Http\Requests\Api\V1\UpdateTicketRequest;
use App\Http\Resources\Api\V1\TicketsResource;
use App\Models\Ticket;
use App\Policies\V1\TicketPolicy;
use App\Traits\ApiResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;

class TicketsController extends ApiController
{
    // Robisz to, żeby w przyszłości jak będziesz zmieniał na wersję 2 api, to w innym controllerze użyjesz v2
    protected $policyClass = TicketPolicy::class;

    use ApiResponse;

    // NOTE dałeś tutaj custom class, która dostaje request, przez to że ma go w construtorze i ważna jest tutaj method "filter" która odpala ten $filter
    /**
     * Index
     *
     * <example description>
     *
     * @group Tickets
     * @queryParam sort string Data field(s) to sort by. Seperate multiple fields with commas. Denote descending sort with a minus sign. Example: sort=title,-createdAt
     * @queryParam filter[status] Filter by status code: A, C, H, X. No-example
     * @response 200 {
     *   "data": "Example success response"
     * }
     * @response 422 {
     *   "errors": "Example error response"
     * }
     */
    public function index(TicketFilter $filter)
    {
        return TicketsResource::collection(Ticket::filter($filter)->paginate());
    }

    /**
     * store
     *
     * <example description>
     *
     * @group Tickets
     * @response 200 {
     *   "data": "Example success response"
     * }
     * @response 422 {
     *   "errors": "Example error response"
     * }
     */
    public function store(StoreTicketRequest $request)
    {
        $data = [
            'title' => $request->get('data.attributes.title'),
            'description' => $request->get('data.attributes.description'),
            'status' => $request->get('data.attributes.status'),
            'user_id' => $request->get('data.relationships.user.data.id'),
        ];

        $ticket = Ticket::create($data);

        return new TicketsResource($ticket);
    }

    /**
     * show
     *
     * <example description>
     *
     * @group Tickets
     * @response 200 {
     *   "data": "Example success response"
     * }
     * @response 422 {
     *   "errors": "Example error response"
     * }
     */
    public function show(Request $request, Ticket $ticket)
    {
        // NOTE usunięo try catch, bo dodano custom reporting w bootstrap/app.php
        $this->isAble('view', $ticket);

        if ($this->include('user')) {
            $ticket->load('user');
        }

        return new TicketsResource($ticket);
    }

    // NOTE Nie używamy Ticket $ticket
    // PATCH
    /**
     * update
     *
     * <example description>
     *
     * @group Tickets
     * @response 200 {
     *   "data": "Example success response"
     * }
     * @response 422 {
     *   "errors": "Example error response"
     * }
     */
    public function update(UpdateTicketRequest $request, $ticketId)
    {
        // PATCH I PUT

        // PATCH updatuje obecny ticket
        // PUT ZMIENIA WSZYSTKIE COLUMNS
    }

    // PUT
    /**
     * replace
     *
     * <example description>
     *
     * @group Tickets
     * @response 200 {
     *   "data": "Example success response"
     * }
     * @response 422 {
     *   "errors": "Example error response"
     * }
     */
    public function replace(ReplaceTicketRequest $request, $ticketId)
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);

            // Możesz do requestu przypisywać methods, żeby tu syfu nie robić
            // $data = $request->mappedAttributes($requst);
            $data = [
                'title' => $request->get('data.attributes.title'),
                'description' => $request->get('data.attributes.description'),
                'status' => $request->get('data.attributes.status'),
                'user_id' => $request->get('data.relationships.user.data.id'),
            ];

            $ticket->update($data);

            return new TicketsResource($ticket);
        } catch (\Throwable $th) {
            return $this->error('We cannot update a ticket');
        }
    }

    // NOTE Nie rób Ticket $ticket, bo wywali error, jak ktoś będzie chciał usunąć rekord którego nie ma
    /**
     * destroy
     *
     * <example description>
     *
     * @group Tickets
     * @response 200 {
     *   "data": "Example success response"
     * }
     * @response 422 {
     *   "errors": "Example error response"
     * }
     */
    public function destroy($ticketId)
    {
        try {
            return $this->deleteTicket($ticketId);
        } catch (\Throwable $th) {
            return $this->error('We cannot delete this ticket');
        }
    }

    private function deleteTicket($ticketId)
    {
        Ticket::findOrFail($ticketId)->delete();

        return $this->ok('Ticket deleted');
    }
}
