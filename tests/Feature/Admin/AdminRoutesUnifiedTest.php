<?php

namespace Tests\Feature\Admin;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminRoutesUnifiedTest extends TestCase
{
    /** @test */
    public function admin_routes_use_a_single_prefix_and_name_group(): void
    {
        // Le préfixe 'admin' et le nom 'admin.' ne doivent être déclarés qu'une seule fois.
        $source = file_get_contents(base_path('routes/web.php'));
        $count = substr_count($source, "name('admin.')");

        $this->assertSame(1, $count, "Le groupe de routes admin.name/prefix ne doit être déclaré qu'une seule fois.");
    }

    /** @test */
    public function admin_dashboard_route_exists_under_admin_namespace(): void
    {
        $route = Route::getRoutes()->getByName('admin.dashboard');

        $this->assertNotNull($route);
        $this->assertSame('admin/dashboard', $route->uri());
        $this->assertContains('auth', $route->gatherMiddleware());
    }

    /** @test */
    public function admin_orders_route_requires_admin_or_employe_role(): void
    {
        $route = Route::getRoutes()->getByName('admin.orders.index');

        $this->assertNotNull($route);
        $this->assertSame('admin/orders', $route->uri());
        $this->assertContains('role:admin,employe', $route->gatherMiddleware());
    }

    /** @test */
    public function admin_users_route_requires_admin_role_only(): void
    {
        $route = Route::getRoutes()->getByName('admin.users.index');

        $this->assertNotNull($route);
        $this->assertSame('admin/users', $route->uri());
        $middleware = $route->gatherMiddleware();

        $this->assertContains('role:admin', $middleware);
        $this->assertNotContains('role:admin,employe', $middleware, 'La route admin.users ne doit pas autoriser les employés.');
    }

    /** @test */
    public function admin_and_employe_can_access_json_charts_endpoint(): void
    {
        $admin = $this->adminUser();
        $employe = $this->employeUser();
        $client = $this->clientUser();

        $this->getJson(route('admin.stats.charts'))
            ->assertUnauthorized();

        $this->actingAs($client)
            ->getJson(route('admin.stats.charts'))
            ->assertForbidden();

        $this->actingAs($employe)
            ->getJson(route('admin.stats.charts'))
            ->assertOk();

        $this->actingAs($admin)
            ->getJson(route('admin.stats.charts'))
            ->assertOk();
    }
}
