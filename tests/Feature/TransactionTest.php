<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Account;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_transactions_index(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->get('/transactions');
            
        $response->assertStatus(200);
    }

    public function test_user_can_create_transaction(): void
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);
        $category = Category::factory()->create(['user_id' => $user->id, 'type' => 'expense']);
        
        $response = $this->actingAs($user)
            ->post('/transactions', [
                'type' => 'expense',
                'amount' => 100000,
                'account_id' => $account->id,
                'category_id' => $category->id,
                'occurred_on' => now()->format('Y-m-d'),
                'payee' => 'Test Store',
                'note' => 'Test transaction'
            ]);
            
        $response->assertRedirect('/transactions');
        
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'type' => 'expense',
            'amount' => 100000,
            'payee' => 'Test Store'
        ]);
    }
}
