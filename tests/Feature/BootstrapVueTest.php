<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BootstrapVueTest extends TestCase
{
    #[Test]
    public function bootstrap_css_est_importe_dans_app_css(): void
    {
        $cssContent = file_get_contents(base_path('resources/css/app.css'));
        $this->assertStringContainsString("@import 'bootstrap", $cssContent);
    }

    #[Test]
    public function vue_js_est_installe_via_npm(): void
    {
        $packageJson = json_decode(file_get_contents(base_path('package.json')), true);
        $this->assertArrayHasKey('vue', $packageJson['dependencies']);
    }

    #[Test]
    public function vite_plugin_vue_est_configure(): void
    {
        $viteConfig = file_get_contents(base_path('vite.config.js'));
        $this->assertStringContainsString("@vitejs/plugin-vue", $viteConfig);
        $this->assertStringContainsString('vue()', $viteConfig);
    }

    #[Test]
    public function app_js_monte_application_vue(): void
    {
        $appJs = file_get_contents(base_path('resources/js/app.js'));
        $this->assertStringContainsString("createApp", $appJs);
        $this->assertStringContainsString("mount('#app')", $appJs);
    }

    #[Test]
    public function composant_vue_test_existe_et_est_fonctionnel(): void
    {
        $this->assertFileExists(resource_path('js/components/TestComponent.vue'));
        
        $component = file_get_contents(resource_path('js/components/TestComponent.vue'));
        $this->assertStringContainsString('<template>', $component);
        $this->assertStringContainsString('</template>', $component);
        $this->assertStringContainsString('<script setup>', $component);
        $this->assertStringContainsString('</script>', $component);
    }
}
