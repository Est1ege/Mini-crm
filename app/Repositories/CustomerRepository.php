<?php

namespace App\Repositories;

use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function create(array $data): Customer
    {
        return Customer::create($data);
    }

    public function findByPhone(string $phone): ?Customer
    {
        return Customer::where('phone', $phone)->first();
    }

    public function findByEmail(string $email): ?Customer
    {
        return Customer::where('email', $email)->first();
    }

    public function findOrCreateByContact(array $data): Customer
    {
        if (!empty($data['phone'])) {
            $customer = $this->findByPhone($data['phone']);
            if ($customer) {
                return $customer;
            }
        }

        if (!empty($data['email'])) {
            $customer = $this->findByEmail($data['email']);
            if ($customer) {
                return $customer;
            }
        }

        return $this->create($data);
    }
}
