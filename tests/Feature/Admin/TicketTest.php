<?php

namespace Tests\Feature\Admin;

use App\Enums\TicketStatus;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'manager']);
    }

    public function test_guest_cannot_access_admin_tickets(): void
    {
        $response = $this->get('/admin/tickets');

        $response->assertRedirect('/login');
    }

    public function test_regular_user_cannot_access_admin_tickets(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/tickets');

        $response->assertStatus(403);
    }

    public function test_manager_can_access_admin_tickets(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        Ticket::factory()->count(3)->create();

        $response = $this->actingAs($user)->get('/admin/tickets');

        $response->assertStatus(200);
    }

    public function test_admin_can_access_admin_tickets(): void
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/admin/tickets');

        $response->assertStatus(200);
    }

    public function test_manager_can_view_ticket_details(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        $ticket = Ticket::factory()->create();

        $response = $this->actingAs($user)->get("/admin/tickets/{$ticket->id}");

        $response->assertStatus(200)
            ->assertSee($ticket->subject);
    }

    public function test_manager_can_update_ticket_status(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        $ticket = Ticket::factory()->newTicket()->create();

        $response = $this->actingAs($user)->patch("/admin/tickets/{$ticket->id}/status", [
            'status' => TicketStatus::IN_PROGRESS->value,
        ]);

        $response->assertRedirect()
            ->assertSessionHas('success');

        $this->assertDatabaseHas('tickets', [
            'id' => $ticket->id,
            'status' => TicketStatus::IN_PROGRESS->value,
        ]);
    }

    public function test_updating_status_to_answered_sets_answered_at(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        $ticket = Ticket::factory()->newTicket()->create();
        $this->assertNull($ticket->answered_at);

        $this->actingAs($user)->patch("/admin/tickets/{$ticket->id}/status", [
            'status' => TicketStatus::ANSWERED->value,
        ]);

        $ticket->refresh();
        $this->assertNotNull($ticket->answered_at);
    }

    public function test_can_filter_tickets_by_status(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        Ticket::factory()->newTicket()->count(2)->create();
        Ticket::factory()->answered()->count(3)->create();

        $response = $this->actingAs($user)->get('/admin/tickets?status=new');

        $response->assertStatus(200);
    }

    public function test_can_filter_tickets_by_date_range(): void
    {
        $user = User::factory()->create();
        $user->assignRole('manager');

        Ticket::factory()->create(['created_at' => now()->subDays(5)]);
        Ticket::factory()->create(['created_at' => now()->subDays(10)]);

        $response = $this->actingAs($user)->get('/admin/tickets?' . http_build_query([
            'from' => now()->subWeek()->toDateString(),
            'to' => now()->toDateString(),
        ]));

        $response->assertStatus(200);
    }
}
