<?php

namespace Tests\Feature\Security;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Tests\TestCase;

class DebugRouteRemovedTest extends TestCase
{
    /** @test */
    public function debug_merge_cart_route_must_not_be_registered(): void
    {
        $this->expectException(NotFoundHttpException::class);

        Route::getRoutes()->match(Request::create('/_debug/merge-cart-test', 'GET'));
    }
}
