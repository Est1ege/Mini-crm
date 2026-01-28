<?php

namespace Tests\Feature\Api;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StatisticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_statistics(): void
    {
        $response = $this->getJson('/api/tickets/statistics');

        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_access_statistics(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Ticket::factory()->count(5)->create();

        $response = $this->getJson('/api/tickets/statistics');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'period' => ['from', 'to'],
                    'total',
                    'by_status',
                    'by_period',
                ],
            ]);
    }

    public function test_can_filter_statistics_by_date_range(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Ticket::factory()->count(3)->create([
            'created_at' => now()->subDays(5),
        ]);

        $response = $this->getJson('/api/tickets/statistics?' . http_build_query([
            'from' => now()->subWeek()->toDateString(),
            'to' => now()->toDateString(),
        ]));

        $response->assertStatus(200);
    }

    public function test_can_group_statistics_by_different_periods(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        Ticket::factory()->count(5)->create();

        $response = $this->getJson('/api/tickets/statistics?group_by=week');

        $response->assertStatus(200);
    }
}
