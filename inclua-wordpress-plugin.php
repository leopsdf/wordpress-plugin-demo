<?php
/**
 * Plugin Name: INCLUA – Demo de Integração (API)
 * Description: Exemplo de plugin que integra WordPress com uma API externa (FastAPI/IA) e expõe um shortcode [inclua_quote].
 * Version: 1.0.0
 * Author: Leonardo de Paiva Souza
 * License: MIT
 */

if (!defined('ABSPATH')) { exit; }

class INCLUA_Demo_Plugin {
    const OPTION_API_BASE = 'inclua_demo_api_base_url';

    public function __construct() {
        add_action('admin_menu', [$this, 'register_admin_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_shortcode('inclua_quote', [$this, 'shortcode_inclua_quote']);
        add_action('admin_enqueue_scripts', [$this, 'admin_assets']);
    }

    public function register_admin_menu() {
        add_menu_page(
            'INCLUA Demo',
            'INCLUA Demo',
            'manage_options',
            'inclua-demo',
            [$this, 'render_settings_page'],
            'dashicons-admin-generic'
        );
    }

    public function register_settings() {
        register_setting('inclua_demo_group', self::OPTION_API_BASE, [
            'type' => 'string',
            'sanitize_callback' => 'esc_url_raw',
            'default' => ''
        ]);
    }

    public function admin_assets($hook) {
        if (strpos($hook, 'inclua-demo') !== false) {
            wp_enqueue_style('inclua-demo-admin', plugin_dir_url(__FILE__) . 'assets/admin.css', [], '1.0.0');
        }
    }

    public function render_settings_page() {
        if (!current_user_can('manage_options')) return;
        include plugin_dir_path(__FILE__) . 'admin/settings-page.php';
    }

    public function shortcode_inclua_quote($atts = [], $content = null) {
        $api_base = esc_url_raw(get_option(self::OPTION_API_BASE, ''));
        if (!$api_base) {
            return '<em>Configure a URL da API em "INCLUA Demo" no painel.</em>';
        }

        $text = isset($atts['text']) ? sanitize_text_field($atts['text']) : 'políticas públicas e equidade';
        $endpoint = trailingslashit($api_base) . 'predict?text=' . rawurlencode($text);

        $response = wp_remote_get($endpoint, [ 'timeout' => 10 ]);
        if (is_wp_error($response)) {
            return '<strong>Erro ao consultar a API:</strong> ' . esc_html($response->get_error_message());
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        if ($code !== 200 || empty($body)) {
            return '<strong>API respondeu com erro:</strong> ' . esc_html($code);
        }

        $data = json_decode($body, true);
        if (!is_array($data)) {
            return '<strong>Resposta inválida da API.</strong>';
        }

        $label = isset($data['label']) ? esc_html($data['label']) : 'N/A';
        $score = isset($data['score']) ? floatval($data['score']) : 0.0;

        ob_start();
        ?>
        <div class="inclua-demo-widget">
            <p><strong>Classificação:</strong> <?php echo $label; ?> (score: <?php echo number_format($score, 3); ?>)</p>
        </div>
        <?php
        return ob_get_clean();
    }
}

new INCLUA_Demo_Plugin();
