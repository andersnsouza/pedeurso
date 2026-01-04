<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\CustomerStatus;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CustomerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Autentica um usuário para todas as requisições
        Sanctum::actingAs(User::factory()->create());
    }

    public function test_can_list_customers(): void
    {
        Customer::factory()->count(3)->create();

        $response = $this->getJson('/api/customers');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'phone',
                        'status',
                        'status_label',
                        'created_at',
                        'updated_at',
                    ],
                ],
                'meta' => [
                    'current_page',
                    'last_page',
                    'per_page',
                    'total',
                ],
            ])
            ->assertJson(['success' => true]);
    }

    public function test_can_create_customer(): void
    {
        $customerData = [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
            'phone' => '11999999999',
            'status' => CustomerStatus::ACTIVE->value,
        ];

        $response = $this->postJson('/api/customers', $customerData);

        $response->assertStatus(201)
            ->assertJson([
                'success' => true,
                'message' => 'Cliente criado com sucesso.',
                'data' => [
                    'name' => 'João Silva',
                    'email' => 'joao@example.com',
                ],
            ]);

        $this->assertDatabaseHas('customers', [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
        ]);
    }

    public function test_cannot_create_customer_with_duplicate_email(): void
    {
        Customer::factory()->create(['email' => 'joao@example.com']);

        $customerData = [
            'name' => 'João Silva',
            'email' => 'joao@example.com',
        ];

        $response = $this->postJson('/api/customers', $customerData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_can_show_customer(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->getJson("/api/customers/{$customer->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                ],
            ]);
    }

    public function test_returns_404_for_nonexistent_customer(): void
    {
        $response = $this->getJson('/api/customers/999');

        $response->assertStatus(404);
    }

    public function test_can_update_customer(): void
    {
        $customer = Customer::factory()->create();

        $updateData = [
            'name' => 'Nome Atualizado',
            'email' => 'novoemail@example.com',
        ];

        $response = $this->putJson("/api/customers/{$customer->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Cliente atualizado com sucesso.',
                'data' => [
                    'name' => 'Nome Atualizado',
                    'email' => 'novoemail@example.com',
                ],
            ]);

        $this->assertDatabaseHas('customers', [
            'id' => $customer->id,
            'name' => 'Nome Atualizado',
            'email' => 'novoemail@example.com',
        ]);
    }

    public function test_can_delete_customer(): void
    {
        $customer = Customer::factory()->create();

        $response = $this->deleteJson("/api/customers/{$customer->id}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'message' => 'Cliente removido com sucesso.',
            ]);

        $this->assertDatabaseMissing('customers', [
            'id' => $customer->id,
        ]);
    }

    public function test_validation_requires_name_and_email(): void
    {
        $response = $this->postJson('/api/customers', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email']);
    }

    public function test_validation_requires_valid_email(): void
    {
        $response = $this->postJson('/api/customers', [
            'name' => 'João',
            'email' => 'invalid-email',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
