<?php

namespace Database\Factories;

use App\Enums\TicketStatus;
use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        $status = fake()->randomElement(TicketStatus::cases());

        return [
            'customer_id' => Customer::factory(),
            'subject' => fake()->sentence(4),
            'text' => fake()->paragraphs(2, true),
            'status' => $status,
            'answered_at' => $status === TicketStatus::ANSWERED ? fake()->dateTimeBetween('-1 month', 'now') : null,
        ];
    }

    public function newTicket(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TicketStatus::NEW,
            'answered_at' => null,
        ]);
    }

    public function answered(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TicketStatus::ANSWERED,
            'answered_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    public function closed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => TicketStatus::CLOSED,
            'answered_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }
}
