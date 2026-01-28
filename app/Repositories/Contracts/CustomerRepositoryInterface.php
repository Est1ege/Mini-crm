<?php

namespace App\Repositories\Contracts;

use App\Models\Customer;

interface CustomerRepositoryInterface
{
    public function create(array $data): Customer;

    public function findByPhone(string $phone): ?Customer;

    public function findByEmail(string $email): ?Customer;

    public function findOrCreateByContact(array $data): Customer;
}
