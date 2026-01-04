<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use App\Services\CustomerService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class CustomerController extends Controller
{
    use ApiResponse;

    public function __construct(
        private readonly CustomerService $customerService
    ) {}

    /**
     * Lista todos os customers.
     */
    public function index(): JsonResponse
    {
        $customers = $this->customerService->list();

        return $this->paginated(
            CustomerResource::collection($customers),
            'Clientes recuperados com sucesso.'
        );
    }

    /**
     * Cria um novo customer.
     */
    public function store(StoreCustomerRequest $request): JsonResponse
    {
        $customer = $this->customerService->create($request->validated());

        return $this->created(
            new CustomerResource($customer),
            'Cliente criado com sucesso.'
        );
    }

    /**
     * Exibe um customer específico.
     */
    public function show(Customer $customer): JsonResponse
    {
        return $this->success(
            new CustomerResource($customer),
            'Cliente recuperado com sucesso.'
        );
    }

    /**
     * Atualiza um customer existente.
     */
    public function update(UpdateCustomerRequest $request, Customer $customer): JsonResponse
    {
        $customer = $this->customerService->update($customer, $request->validated());

        return $this->success(
            new CustomerResource($customer),
            'Cliente atualizado com sucesso.'
        );
    }

    /**
     * Remove um customer.
     */
    public function destroy(Customer $customer): JsonResponse
    {
        $this->customerService->delete($customer);

        return $this->deleted('Cliente removido com sucesso.');
    }
}
