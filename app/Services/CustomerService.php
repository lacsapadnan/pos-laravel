<?php

namespace App\Services;

use App\Models\Customer;
use App\Repositories\Interface\CustomerRepositoryInterface;
use App\Services\Interface\CustomerServiceInterface;

final class CustomerService implements CustomerServiceInterface
{
    protected CustomerRepositoryInterface $customerRepository;

    /**
     * Create a new class instance.
     */
    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function getAllCustomers(): \Illuminate\Support\Collection
    {
        return $this->customerRepository->all();
    }

    public function getCustomerById(int $id): Customer
    {
        return $this->customerRepository->findById($id);
    }

    public function createCustomer(array $data): Customer
    {
        return $this->customerRepository->create($data);
    }

    public function updateCustomer(int $id, array $data): bool
    {
        return $this->customerRepository->update($id, $data);
    }

    public function deleteCustomer(int $id): bool
    {
        return $this->customerRepository->delete($id);
    }
}
