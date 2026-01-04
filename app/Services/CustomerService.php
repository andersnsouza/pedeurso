<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Customer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CustomerService
{
    /**
     * Lista todos os customers com paginação.
     */
    public function list(int $perPage = 15): LengthAwarePaginator
    {
        return Customer::query()
            ->orderBy('name')
            ->paginate($perPage);
    }

    /**
     * Cria um novo customer.
     *
     * @param array<string, mixed> $data
     */
    public function create(array $data): Customer
    {
        return Customer::create($data);
    }

    /**
     * Busca um customer pelo ID.
     */
    public function find(int $id): ?Customer
    {
        return Customer::find($id);
    }

    /**
     * Atualiza um customer existente.
     *
     * @param array<string, mixed> $data
     */
    public function update(Customer $customer, array $data): Customer
    {
        $customer->update($data);

        return $customer->fresh();
    }

    /**
     * Remove um customer.
     */
    public function delete(Customer $customer): bool
    {
        return $customer->delete();
    }
}
