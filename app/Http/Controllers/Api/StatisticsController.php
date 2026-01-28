<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\StatisticsResource;
use App\Services\StatisticsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    public function __construct(
        private StatisticsService $statisticsService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'group_by' => ['nullable', 'in:day,week,month'],
        ]);

        $from = $validated['from'] ?? now()->subMonth()->toDateString();
        $to = $validated['to'] ?? now()->toDateString();
        $groupBy = $validated['group_by'] ?? 'day';

        $statistics = $this->statisticsService->getStatistics($from, $to, $groupBy);

        return response()->json([
            'data' => new StatisticsResource($statistics),
        ]);
    }
}
