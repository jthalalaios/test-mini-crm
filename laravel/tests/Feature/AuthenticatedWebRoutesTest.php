<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Company;
use App\Models\Employee;

class AuthenticatedWebRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $admin_role = \App\Models\Role::firstOrCreate([
            'slug' => 'admin',
        ], [
            'name' => 'Admin',
        ]);
        \App\Helpers\RolesHelper::insert_user_role_by_id($this->user->id, $admin_role->id);
    }

    public function test_companies_index_authenticated()
    {
        $response = $this->actingAs($this->user)->get('/companies');
        $response->assertStatus(200);
        $response->assertSee('Companies', false); // Adjust as needed
    }

    public function test_employees_index_authenticated()
    {
        $response = $this->actingAs($this->user)->get('/employees');
        $response->assertStatus(200);
        $response->assertSee('Employees', false); // Adjust as needed
    }

    public function test_companies_datatable_ajax()
    {
        $response = $this->actingAs($this->user)
            ->getJson('/companies/datatable');
        $response->assertStatus(200);
        $response->assertJsonStructure(['draw', 'recordsTotal', 'recordsFiltered', 'data']);
    }

    public function test_employees_datatable_ajax_search_company_name()
    {
        // Create a company and employee
        $company = Company::factory()->create(['name' => 'TestCompany']);
        $employee = Employee::factory()->create(['company_id' => $company->id]);

        $response = $this->actingAs($this->user)->get(
            '/employees',
            [
                'HTTP_X-Requested-With' => 'XMLHttpRequest',
                'filter' => ['company_name' => 'TestCompany'],
                'items' => 10,
            ]
        );
        $response->assertStatus(200);
        // The company_name is now an HTML link, so check for the company name in the response
        $response->assertSee('TestCompany', false);
    }

    public function test_companies_datatable_ajax_with_data()
    {
        // Create a company
        $company = Company::factory()->create(['name' => 'TestCompany', 'email' => 'test@company.com', 'website' => 'https://testcompany.com']);

        $response = $this->actingAs($this->user)
            ->getJson('/companies/datatable?filter[name]=TestCompany&items=10');
        $response->assertStatus(200);
        $response->assertJsonFragment(['name' => 'TestCompany']);
    }

    // Add more CRUD and AJAX tests as needed
}
