<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Company;
use App\Models\Employee;

class WebRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_loads()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('login', false); // Adjust as needed
    }

    public function test_companies_index_requires_auth()
    {
        $response = $this->get('/companies');
        $response->assertRedirect('/');
    }

    public function test_employees_index_requires_auth()
    {
        $response = $this->get('/employees');
        $response->assertRedirect('/');
    }

    // Add more tests for authenticated routes, CRUD, and AJAX as needed
}
