<?php

namespace Tests\Unit\Services;

use App\Enums\TicketStatus;
use App\Models\Customer;
use App\Models\Ticket;
use App\Repositories\CustomerRepository;
use App\Repositories\TicketRepository;
use App\Services\FileService;
use App\Services\TicketService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketServiceTest extends TestCase
{
    use RefreshDatabase;

    private TicketService $ticketService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->ticketService = new TicketService(
            new TicketRepository(),
            new CustomerRepository(),
            new FileService(),
        );
    }

    public function test_creates_ticket_with_new_customer(): void
    {
        $ticket = $this->ticketService->createTicket([
            'name' => 'John Doe',
            'phone' => '+79001234567',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'text' => 'Test message',
        ]);

        $this->assertInstanceOf(Ticket::class, $ticket);
        $this->assertEquals('Test Subject', $ticket->subject);
        $this->assertEquals(TicketStatus::NEW, $ticket->status);
        $this->assertEquals('John Doe', $ticket->customer->name);
    }

    public function test_creates_ticket_with_existing_customer(): void
    {
        $customer = Customer::factory()->create([
            'phone' => '+79001234567',
        ]);

        $ticket = $this->ticketService->createTicket([
            'name' => 'John Doe',
            'phone' => '+79001234567',
            'subject' => 'Test Subject',
            'text' => 'Test message',
        ]);

        $this->assertEquals($customer->id, $ticket->customer_id);
    }

    public function test_can_update_ticket_status(): void
    {
        $ticket = Ticket::factory()->newTicket()->create();

        $updatedTicket = $this->ticketService->updateStatus($ticket->id, TicketStatus::IN_PROGRESS);

        $this->assertEquals(TicketStatus::IN_PROGRESS, $updatedTicket->status);
    }

    public function test_sets_answered_at_when_status_changed_to_answered(): void
    {
        $ticket = Ticket::factory()->newTicket()->create();
        $this->assertNull($ticket->answered_at);

        $updatedTicket = $this->ticketService->updateStatus($ticket->id, TicketStatus::ANSWERED);

        $this->assertNotNull($updatedTicket->answered_at);
    }

    public function test_can_create_ticket_returns_true_for_new_contact(): void
    {
        $result = $this->ticketService->canCreateTicket('+79001234567', 'test@example.com');

        $this->assertTrue($result);
    }

    public function test_can_create_ticket_returns_false_for_recent_contact(): void
    {
        $customer = Customer::factory()->create([
            'phone' => '+79001234567',
        ]);
        Ticket::factory()->create([
            'customer_id' => $customer->id,
        ]);

        $result = $this->ticketService->canCreateTicket('+79001234567', null);

        $this->assertFalse($result);
    }

    public function test_can_create_ticket_returns_false_when_no_contact_provided(): void
    {
        $result = $this->ticketService->canCreateTicket(null, null);

        $this->assertFalse($result);
    }
}
