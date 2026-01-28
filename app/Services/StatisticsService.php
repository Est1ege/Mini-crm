<?php

namespace App\Services;

use App\Repositories\Contracts\TicketRepositoryInterface;
use Illuminate\Support\Collection;

class StatisticsService
{
    public function __construct(
        private TicketRepositoryInterface $ticketRepository,
    ) {}

    public function getStatistics(string $from, string $to, string $groupBy = 'day'): array
    {
        $byStatus = $this->ticketRepository->getCountByStatus($from, $to);
        $byPeriod = $this->ticketRepository->getCountByPeriod($from, $to, $groupBy);

        $total = $byStatus->sum('count');

        return [
            'period' => [
                'from' => $from,
                'to' => $to,
            ],
            'total' => $total,
            'by_status' => $this->formatByStatus($byStatus),
            'by_period' => $byPeriod->pluck('count', 'period')->toArray(),
        ];
    }

    private function formatByStatus(Collection $data): array
    {
        return $data->mapWithKeys(fn($item) => [
            $item->status => $item->count
        ])->toArray();
    }
}
