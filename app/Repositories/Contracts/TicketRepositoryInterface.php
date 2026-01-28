<?php

namespace App\Repositories\Contracts;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface TicketRepositoryInterface
{
    public function create(array $data): Ticket;

    public function find(int $id): ?Ticket;

    public function findOrFail(int $id): Ticket;

    public function updateStatus(int $id, TicketStatus $status): Ticket;

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    public function getCountByStatus(string $from, string $to): Collection;

    public function getCountByPeriod(string $from, string $to, string $groupBy = 'day'): Collection;

    public function hasRecentTicket(string $phone = null, string $email = null, int $hours = 24): bool;
}
