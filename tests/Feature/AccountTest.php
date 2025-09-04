<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Account;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_accounts_index(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->get('/accounts');
            
        $response->assertStatus(200);
    }

    public function test_user_can_create_account(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->post('/accounts', [
                'name' => 'Test Bank Account',
                'type' => 'bank',
                'currency' => 'IDR',
                'starting_balance' => 1000000,
                'note' => 'Test account'
            ]);
            
        $response->assertRedirect('/accounts');
        
        $this->assertDatabaseHas('accounts', [
            'user_id' => $user->id,
            'name' => 'Test Bank Account',
            'type' => 'bank',
            'currency' => 'IDR',
            'starting_balance' => 1000000,
            'current_balance' => 1000000
        ]);
    }

    public function test_user_can_create_credit_card_account(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->post('/accounts', [
                'name' => 'Test Credit Card',
                'type' => 'credit-card',
                'currency' => 'USD',
                'starting_balance' => 0,
                'note' => 'Test credit card account'
            ]);
            
        $response->assertRedirect('/accounts');
        
        $this->assertDatabaseHas('accounts', [
            'user_id' => $user->id,
            'name' => 'Test Credit Card',
            'type' => 'credit-card',
            'currency' => 'USD',
            'starting_balance' => 0,
            'current_balance' => 0
        ]);
    }

    public function test_user_can_update_account(): void
    {
        $user = User::factory()->create();
        $account = Account::factory()->create(['user_id' => $user->id]);
        
        $response = $this->actingAs($user)
            ->put("/accounts/{$account->id}", [
                'name' => 'Updated Account Name',
                'type' => 'cash',
                'currency' => 'USD',
                'note' => 'Updated note'
            ]);
            
        $response->assertRedirect('/accounts');
        
        $this->assertDatabaseHas('accounts', [
            'id' => $account->id,
            'name' => 'Updated Account Name',
            'type' => 'cash',
            'currency' => 'USD'
        ]);
    }
}
