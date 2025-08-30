<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Budget;
use App\Models\Category;
use App\Models\BudgetCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BudgetTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_budgets_index(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
            ->get('/budgets');
            
        $response->assertStatus(200);
    }

    public function test_user_can_create_budget(): void
    {
        $user = User::factory()->create();
        $category = Category::factory()->create([
            'user_id' => $user->id,
            'type' => 'expense'
        ]);
        
        $response = $this->actingAs($user)
            ->post('/budgets', [
                'year' => 2025,
                'month' => 8,
                'categories' => [
                    [
                        'category_id' => $category->id,
                        'amount' => 1000000
                    ]
                ]
            ]);
            
        $response->assertRedirect('/budgets');
        
        $this->assertDatabaseHas('budgets', [
            'user_id' => $user->id,
            'month' => '2025-08',
            'total_limit' => 1000000
        ]);
        
        $this->assertDatabaseHas('budget_categories', [
            'category_id' => $category->id,
            'limit_amount' => 1000000
        ]);
    }

    public function test_user_can_view_budget_details(): void
    {
        $user = User::factory()->create();
        $budget = Budget::factory()->create(['user_id' => $user->id]);
        
        $response = $this->actingAs($user)
            ->get("/budgets/{$budget->id}");
            
        $response->assertStatus(200);
    }
}
