<?php
/**
 * WordPress integration test for product/category/brand term relationships.
 *
 * Run inside a bootstrapped WordPress installation:
 * php tests/integration/test-brand-aggregation.php
 */

if (!defined('ABSPATH')) {
    fwrite(STDERR, "Run this test through a WordPress bootstrap.\n");
    exit(1);
}

require_once dirname(__DIR__, 2) . '/marcas-por-categoria.php';

function integration_assert_same($expected, $actual, $message) {
    if ($expected !== $actual) {
        fwrite(STDERR, "FAIL: {$message}\n");
        fwrite(STDERR, 'Expected: ' . var_export($expected, true) . "\n");
        fwrite(STDERR, 'Actual:   ' . var_export($actual, true) . "\n");
        exit(1);
    }

    echo "PASS: {$message}\n";
}

function integration_get_brands($plugin, $category) {
    $method = new ReflectionMethod('MarcasPorCategoria', 'get_marcas_por_categoria');
    $method->setAccessible(true);

    return $method->invoke($plugin, $category);
}

// WooCommerce registers product and product_cat. Register minimal equivalents
// only when the installed WooCommerce version/provider does not expose them.
if (!post_type_exists('product')) {
    register_post_type('product', ['public' => true]);
}

if (!taxonomy_exists('product_cat')) {
    register_taxonomy('product_cat', ['product'], ['hierarchical' => true]);
}

if (!taxonomy_exists('product_brand')) {
    register_taxonomy('product_brand', ['product'], ['hierarchical' => false]);
}

$category_result = wp_insert_term('Integration Category', 'product_cat');
$acme_result = wp_insert_term('Integration Acme', 'product_brand');
$globex_result = wp_insert_term('Integration Globex', 'product_brand');

foreach ([$category_result, $acme_result, $globex_result] as $result) {
    if (is_wp_error($result)) {
        fwrite(STDERR, $result->get_error_message() . "\n");
        exit(1);
    }
}

$category = get_term($category_result['term_id'], 'product_cat');
$acme_id = (int) $acme_result['term_id'];
$globex_id = (int) $globex_result['term_id'];
$product_ids = [];

try {
    foreach (['Product One', 'Product Two', 'Product Three'] as $title) {
        $product_id = wp_insert_post([
            'post_type'   => 'product',
            'post_status' => 'publish',
            'post_title'  => $title,
        ], true);

        if (is_wp_error($product_id)) {
            throw new RuntimeException($product_id->get_error_message());
        }

        $product_ids[] = (int) $product_id;
        wp_set_object_terms($product_id, [$category->term_id], 'product_cat');
    }

    wp_set_object_terms($product_ids[0], [$acme_id, $globex_id], 'product_brand');
    wp_set_object_terms($product_ids[1], [$acme_id], 'product_brand');
    wp_set_object_terms($product_ids[2], [$acme_id], 'product_brand');

    $plugin = new MarcasPorCategoria();
    $brands = integration_get_brands($plugin, $category);

    integration_assert_same(3, $brands['integration-acme']['count'] ?? null, 'counts three real Acme product relationships');
    integration_assert_same(1, $brands['integration-globex']['count'] ?? null, 'counts one real Globex product relationship');
    integration_assert_same(['integration-acme', 'integration-globex'], array_keys($brands), 'returns each brand once, grouped by slug');

    $empty_category_result = wp_insert_term('Empty Integration Category', 'product_cat');
    if (is_wp_error($empty_category_result)) {
        throw new RuntimeException($empty_category_result->get_error_message());
    }

    $empty_category = get_term($empty_category_result['term_id'], 'product_cat');
    integration_assert_same([], integration_get_brands($plugin, $empty_category), 'returns an empty result for a category without products');

    wp_delete_term($empty_category->term_id, 'product_cat');
    echo "\nAll WordPress integration tests passed.\n";
} finally {
    foreach ($product_ids as $product_id) {
        wp_delete_post($product_id, true);
    }

    wp_delete_term($category->term_id, 'product_cat');
    wp_delete_term($acme_id, 'product_brand');
    wp_delete_term($globex_id, 'product_brand');
}
