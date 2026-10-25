<?php
/**
 * Plugin Name: Marcas por Categoría (Logos) - WooCommerce Nativo
 * Description: Muestra logos de marcas presentes en la categoría actual de WooCommerce y construye URLs por categoría + marca. Compatible con taxonomía nativa product_brand.
 * Version: 4.2
 * Author: Marco
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class MarcasPorCategoria {

    private $shortcode_used = false;

    public function __construct() {
        add_shortcode('marcas_categoria', [$this, 'shortcode_output']);
        add_action('wp_footer', [$this, 'enqueue_styles']);
    }

    public function enqueue_styles() {

        if (!$this->shortcode_used) return;

        echo '
        <style>

            ul.marcas-logos {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                padding: 0;
                margin: 15px 0;
                list-style: none;
            }

            ul.marcas-logos li.marca-item {
                flex: 0 0 auto;
                width: 85px;
                height: 85px;
                position: relative;
            }

            ul.marcas-logos li.marca-item a {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 100%;
                height: 100%;
                padding: 10px;
                background: #ffffff;
                border: 1px solid #e0e0e0;
                border-radius: 14px;
                text-decoration: none;
                position: relative;
                transition: all 0.2s ease;
            }

            ul.marcas-logos li.marca-item a:hover {
                border-color: #02aefd;
            }

            ul.marcas-logos li.marca-item img {
                max-width: 100%;
                max-height: 100%;
                width: auto;
                height: auto;
                object-fit: contain;
            }

            ul.marcas-logos li.marca-item .marca-text {
                font-size: 11px;
                font-weight: bold;
                text-align: center;
                line-height: 1.2;
                word-break: break-word;
            }

            ul.marcas-logos li.marca-item .marca-count {
                position: absolute;
                bottom: 4px;
                right: 4px;
                background: #333;
                color: #0fc9f2;
                font-size: 10px;
                font-weight: bold;
                padding: 3px 6px;
                border-radius: 12px;
                line-height: 1;
            }

            /* ===== Marca Activa ===== */

            ul.marcas-logos li.marca-item a.active {
                border: 1px solid #02aefd;
                box-shadow: 0 0 0 3px rgba(2, 174, 253, 0.15);
            }

            ul.marcas-logos li.marca-item a.active .marca-count {
                background: #02aefd;
                color: #040424;
            }

            /* ===== Botón X para limpiar filtro ===== */

            ul.marcas-logos li.marca-item .marca-clear {
                position: absolute;
                top: -6px;
                right: -6px;
                width: 20px;
                height: 20px;
                background: #02aefd;
                color: #fff;
                border: 2px solid #fff;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                font-size: 12px;
                font-weight: bold;
                line-height: 1;
                z-index: 2;
                transition: all 0.2s ease;
                text-decoration: none;
                box-shadow: 0 2px 4px rgba(0,0,0,0.15);
            }

            ul.marcas-logos li.marca-item .marca-clear:hover {
                background: #ff4444;
                transform: scale(1.1);
            }

        </style>
        ';
    }

    private function get_marcas_por_categoria($category) {

        if (!$category) return [];

        $args = [
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'tax_query'      => [
                [
                    'taxonomy' => 'product_cat',
                    'field'    => 'term_id',
                    'terms'    => $category->term_id,
                ],
            ],
        ];

        $products = get_posts($args);
        if (empty($products)) return [];

        $marcas = [];

        foreach ($products as $product_id) {

            $terms = get_the_terms($product_id, 'product_brand');
            if (!$terms || is_wp_error($terms)) continue;

            foreach ($terms as $term) {

                $slug = $term->slug;

                if (!isset($marcas[$slug])) {

                    $thumb_id = get_term_meta($term->term_id, 'thumbnail_id', true);
                    $img_url  = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'medium') : '';

                    $base_url = get_term_link($category, 'product_cat');
                    if (is_wp_error($base_url)) continue;

                    $current_brand = isset($_GET['product_brand'])
                        ? sanitize_text_field($_GET['product_brand'])
                        : '';

                    // Toggle: si ya está activa, quitar filtro
                    if ($current_brand === $slug) {
                        $url = remove_query_arg('product_brand', $base_url);
                    } else {
                        $url = add_query_arg('product_brand', $slug, $base_url);
                    }

                    $marcas[$slug] = [
                        'name'  => $term->name,
                        'img'   => $img_url,
                        'url'   => $url,
                        'count' => 0,
                    ];
                }

                $marcas[$slug]['count']++;
            }
        }

        return $marcas;
    }

    public function shortcode_output() {

        $category = null;

        if (is_product_category()) {
            $category = get_queried_object();
        } elseif (isset($_GET['product_cat'])) {
            $category = get_term_by(
                'slug',
                sanitize_text_field($_GET['product_cat']),
                'product_cat'
            );
        }

        if (!$category) return '';

        $marcas = $this->get_marcas_por_categoria($category);
        if (empty($marcas)) return '';

        $this->shortcode_used = true;

        $active_brand = isset($_GET['product_brand'])
            ? sanitize_text_field($_GET['product_brand'])
            : '';

        // URL para limpiar filtro
        $clear_url = $category ? get_term_link($category, 'product_cat') : '';

        $html = '<ul class="marcas-logos">';

        foreach ($marcas as $slug => $marca) {

            $active_class = ($active_brand === $slug) ? ' active' : '';

            $html .= '<li class="marca-item">';
            $html .= '<a class="' . $active_class . '" href="' . esc_url($marca['url']) . '" title="' . esc_attr($marca['name'] . ' (' . $marca['count'] . ' productos)') . '">';

            if (!empty($marca['img'])) {
                $html .= '<img src="' . esc_url($marca['img']) . '" alt="' . esc_attr($marca['name']) . '">';
            } else {
                $html .= '<span class="marca-text">' . esc_html($marca['name']) . '</span>';
            }

            $html .= '<span class="marca-count">' . $marca['count'] . '</span>';
            $html .= '</a>';

            // Mostrar X solo si esta marca está activa
            if ($active_brand === $slug && $clear_url) {
                $html .= '<a href="' . esc_url($clear_url) . '" class="marca-clear" title="Limpiar filtro">×</a>';
            }

            $html .= '</li>';
        }

        $html .= '</ul>';

        return $html;
    }
}

new MarcasPorCategoria();