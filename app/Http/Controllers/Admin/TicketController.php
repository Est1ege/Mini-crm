<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TicketStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\TicketFilterRequest;
use App\Http\Requests\UpdateTicketStatusRequest;
use App\Repositories\Contracts\TicketRepositoryInterface;
use App\Services\TicketService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\StreamedResponse;

class TicketController extends Controller
{
    public function __construct(
        private TicketRepositoryInterface $ticketRepository,
        private TicketService $ticketService,
    ) {}

    public function index(TicketFilterRequest $request): View
    {
        $tickets = $this->ticketRepository->paginate(
            $request->validated(),
            $request->integer('per_page', 15)
        );

        return view('admin.tickets.index', [
            'tickets' => $tickets,
            'statuses' => TicketStatus::cases(),
            'filters' => $request->validated(),
        ]);
    }

    public function show(int $id): View
    {
        $ticket = $this->ticketRepository->findOrFail($id);

        return view('admin.tickets.show', [
            'ticket' => $ticket,
            'statuses' => TicketStatus::cases(),
        ]);
    }

    public function updateStatus(UpdateTicketStatusRequest $request, int $id): RedirectResponse
    {
        $status = TicketStatus::from($request->validated('status'));
        $this->ticketService->updateStatus($id, $status);

        return redirect()
            ->route('admin.tickets.show', $id)
            ->with('success', 'Status updated successfully');
    }

    public function download(int $ticketId, int $mediaId): StreamedResponse
    {
        $ticket = $this->ticketRepository->findOrFail($ticketId);
        $media = $ticket->media->find($mediaId);

        if (!$media) {
            abort(404, 'File not found');
        }

        return response()->streamDownload(function () use ($media) {
            echo file_get_contents($media->getPath());
        }, $media->file_name, [
            'Content-Type' => $media->mime_type,
        ]);
    }
}
