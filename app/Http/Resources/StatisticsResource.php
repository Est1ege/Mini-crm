<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StatisticsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'period' => $this->resource['period'],
            'total' => $this->resource['total'],
            'by_status' => $this->resource['by_status'],
            'by_period' => $this->resource['by_period'],
        ];
    }
}
