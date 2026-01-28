<?php

namespace Tests\Feature\Api;

use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_ticket_with_valid_data(): void
    {
        $response = $this->postJson('/api/tickets', [
            'name' => 'John Doe',
            'phone' => '+79001234567',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'text' => 'This is a test message.',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'id',
                    'subject',
                    'text',
                    'status',
                    'created_at',
                ],
            ]);

        $this->assertDatabaseHas('tickets', [
            'subject' => 'Test Subject',
            'status' => 'new',
        ]);

        $this->assertDatabaseHas('customers', [
            'name' => 'John Doe',
            'phone' => '+79001234567',
            'email' => 'john@example.com',
        ]);
    }

    public function test_cannot_create_ticket_without_contact_info(): void
    {
        $response = $this->postJson('/api/tickets', [
            'name' => 'John Doe',
            'subject' => 'Test Subject',
            'text' => 'This is a test message.',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['phone', 'email']);
    }

    public function test_can_create_ticket_with_only_phone(): void
    {
        $response = $this->postJson('/api/tickets', [
            'name' => 'John Doe',
            'phone' => '+79001234567',
            'subject' => 'Test Subject',
            'text' => 'This is a test message.',
        ]);

        $response->assertStatus(201);
    }

    public function test_can_create_ticket_with_only_email(): void
    {
        $response = $this->postJson('/api/tickets', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'text' => 'This is a test message.',
        ]);

        $response->assertStatus(201);
    }

    public function test_validates_phone_e164_format(): void
    {
        $response = $this->postJson('/api/tickets', [
            'name' => 'John Doe',
            'phone' => '12345',
            'subject' => 'Test Subject',
            'text' => 'This is a test message.',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['phone']);
    }

    public function test_cannot_create_ticket_within_24_hours_from_same_contact(): void
    {
        $customer = Customer::factory()->create([
            'phone' => '+79001234567',
            'email' => 'john@example.com',
        ]);

        Ticket::factory()->create([
            'customer_id' => $customer->id,
        ]);

        $response = $this->postJson('/api/tickets', [
            'name' => 'John Doe',
            'phone' => '+79001234567',
            'subject' => 'Another Subject',
            'text' => 'Another message.',
        ]);

        $response->assertStatus(429)
            ->assertJson([
                'message' => 'You can only submit one ticket per day',
            ]);
    }

    public function test_can_create_ticket_with_file_attachments(): void
    {
        Storage::fake('local');

        $file = UploadedFile::fake()->create('document.pdf', 1024);

        $response = $this->postJson('/api/tickets', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'subject' => 'Test Subject',
            'text' => 'This is a test message.',
            'files' => [$file],
        ]);

        $response->assertStatus(201);

        $ticket = Ticket::first();
        $this->assertEquals(1, $ticket->getMedia('attachments')->count());
    }

    public function test_required_fields_validation(): void
    {
        $response = $this->postJson('/api/tickets', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'subject', 'text']);
    }
}
