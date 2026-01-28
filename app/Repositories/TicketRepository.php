<?php

namespace App\Repositories;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Repositories\Contracts\TicketRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TicketRepository implements TicketRepositoryInterface
{
    public function create(array $data): Ticket
    {
        return Ticket::create($data);
    }

    public function find(int $id): ?Ticket
    {
        return Ticket::with('customer', 'media')->find($id);
    }

    public function findOrFail(int $id): Ticket
    {
        return Ticket::with('customer', 'media')->findOrFail($id);
    }

    public function updateStatus(int $id, TicketStatus $status): Ticket
    {
        $ticket = $this->findOrFail($id);

        $data = ['status' => $status];

        if ($status === TicketStatus::ANSWERED) {
            $data['answered_at'] = now();
        }

        $ticket->update($data);

        return $ticket->fresh();
    }

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Ticket::with('customer', 'media');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['email'])) {
            $query->byCustomerEmail($filters['email']);
        }

        if (!empty($filters['phone'])) {
            $query->byCustomerPhone($filters['phone']);
        }

        if (!empty($filters['from'])) {
            $query->where('created_at', '>=', $filters['from']);
        }

        if (!empty($filters['to'])) {
            $query->where('created_at', '<=', $filters['to']);
        }

        return $query->orderByDesc('created_at')->paginate($perPage);
    }

    public function getCountByStatus(string $from, string $to): Collection
    {
        return Ticket::query()
            ->selectRaw('status, COUNT(*) as count')
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('status')
            ->get();
    }

    public function getCountByPeriod(string $from, string $to, string $groupBy = 'day'): Collection
    {
        $format = match ($groupBy) {
            'month' => 'YYYY-MM',
            'week' => 'IYYY-IW',
            default => 'YYYY-MM-DD',
        };

        return Ticket::query()
            ->selectRaw("TO_CHAR(created_at, '{$format}') as period, COUNT(*) as count")
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    }

    public function hasRecentTicket(string $phone = null, string $email = null, int $hours = 24): bool
    {
        $query = Ticket::query()
            ->where('created_at', '>=', now()->subHours($hours));

        if ($phone) {
            $query->whereHas('customer', fn($q) => $q->where('phone', $phone));
        }

        if ($email) {
            $query->orWhereHas('customer', fn($q) => $q->where('email', $email));
        }

        return $query->exists();
    }
}
