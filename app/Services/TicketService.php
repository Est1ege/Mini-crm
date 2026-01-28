<?php

namespace App\Services;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Repositories\Contracts\CustomerRepositoryInterface;
use App\Repositories\Contracts\TicketRepositoryInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class TicketService
{
    public function __construct(
        private TicketRepositoryInterface $ticketRepository,
        private CustomerRepositoryInterface $customerRepository,
        private FileService $fileService,
    ) {}

    public function createTicket(array $data, array $files = []): Ticket
    {
        return DB::transaction(function () use ($data, $files) {
            $customer = $this->customerRepository->findOrCreateByContact([
                'name' => $data['name'],
                'phone' => $data['phone'] ?? null,
                'email' => $data['email'] ?? null,
            ]);

            $ticket = $this->ticketRepository->create([
                'customer_id' => $customer->id,
                'subject' => $data['subject'],
                'text' => $data['text'],
                'status' => TicketStatus::NEW,
            ]);

            if (!empty($files)) {
                $this->fileService->attachFiles($ticket, $files);
            }

            return $ticket->load('customer', 'media');
        });
    }

    public function updateStatus(int $ticketId, TicketStatus $status): Ticket
    {
        return $this->ticketRepository->updateStatus($ticketId, $status);
    }

    public function canCreateTicket(?string $phone, ?string $email): bool
    {
        if (!$phone && !$email) {
            return false;
        }

        return !$this->ticketRepository->hasRecentTicket($phone, $email, 24);
    }
}
