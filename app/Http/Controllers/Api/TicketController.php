<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Resources\TicketResource;
use App\Services\TicketService;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public function __construct(
        private TicketService $ticketService,
    ) {}

    public function store(StoreTicketRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if (!$this->ticketService->canCreateTicket($validated['phone'] ?? null, $validated['email'] ?? null)) {
            return response()->json([
                'message' => 'You can only submit one ticket per day',
            ], 429);
        }

        $ticket = $this->ticketService->createTicket(
            $validated,
            $request->file('files', [])
        );

        return response()->json([
            'message' => 'Ticket created successfully',
            'data' => new TicketResource($ticket),
        ], 201);
    }
}
