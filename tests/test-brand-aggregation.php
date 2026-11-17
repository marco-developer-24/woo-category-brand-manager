<?php
/**
 * Lightweight regression tests for brand aggregation.
 *
 * Run with: php tests/test-brand-aggregation.php
 */

define('ABSPATH', __DIR__ . '/');

$GLOBALS['test_products'] = [101, 102, 103];
$GLOBALS['test_brand_terms'] = [];
$GLOBALS['test_term_error'] = false;
$GLOBALS['test_term_link_error'] = false;
$GLOBALS['test_term_calls'] = 0;
$GLOBALS['test_last_term_args'] = null;

class Test_WP_Error {}

function add_shortcode($tag, $callback) {}
function add_action($hook, $callback) {}
function is_wp_error($value) {
    return $value instanceof Test_WP_Error;
}
function get_posts($args) {
    return $GLOBALS['test_products'];
}
function wp_get_object_terms($object_ids, $taxonomy, $args = []) {
    $GLOBALS['test_term_calls']++;
    $GLOBALS['test_last_term_args'] = [$object_ids, $taxonomy, $args];

    if ($GLOBALS['test_term_error']) {
        return new Test_WP_Error();
    }

    return $GLOBALS['test_brand_terms'];
}
function get_term_link($term, $taxonomy = '') {
    return $GLOBALS['test_term_link_error']
        ? new Test_WP_Error()
        : 'https://example.test/product-category/components/';
}
function get_term_meta($term_id, $key, $single = false) {
    return 0;
}
function wp_get_attachment_image_url($attachment_id, $size = 'thumbnail') {
    return false;
}
function sanitize_title($value) {
    return strtolower(trim($value));
}
function wp_unslash($value) {
    return $value;
}
function remove_query_arg($key, $url = '') {
    return $url;
}
function add_query_arg($key, $value, $url = '') {
    return $url . '?' . rawurlencode($key) . '=' . rawurlencode($value);
}

require dirname(__DIR__) . '/marcas-por-categoria.php';

function test_assert_same($expected, $actual, $message) {
    if ($expected !== $actual) {
        fwrite(STDERR, "FAIL: {$message}\n");
        fwrite(STDERR, 'Expected: ' . var_export($expected, true) . "\n");
        fwrite(STDERR, 'Actual:   ' . var_export($actual, true) . "\n");
        exit(1);
    }

    echo "PASS: {$message}\n";
}

function test_term($term_id, $name, $slug, $object_id) {
    return (object) [
        'term_id'   => $term_id,
        'name'      => $name,
        'slug'      => $slug,
        'object_id' => $object_id,
    ];
}

function invoke_brand_lookup($plugin, $category) {
    $method = new ReflectionMethod('MarcasPorCategoria', 'get_marcas_por_categoria');
    $method->setAccessible(true);

    return $method->invoke($plugin, $category);
}

$plugin = new MarcasPorCategoria();
$category = (object) ['term_id' => 55];

$GLOBALS['test_brand_terms'] = [
    test_term(7, 'Acme', 'acme', 101),
    test_term(8, 'Globex', 'globex', 101),
    test_term(7, 'Acme', 'acme', 102),
    test_term(7, 'Acme', 'acme', 103),
];

$_GET = [];
$GLOBALS['test_term_calls'] = 0;

$brands = invoke_brand_lookup($plugin, $category);

test_assert_same(['acme', 'globex'], array_keys($brands), 'groups brand terms by slug');
test_assert_same(3, $brands['acme']['count'], 'counts each Acme product relationship');
test_assert_same(1, $brands['globex']['count'], 'counts a product assigned to another brand');
test_assert_same(1, $GLOBALS['test_term_calls'], 'retrieves brand relationships in one batch');
test_assert_same('all_with_object_id', $GLOBALS['test_last_term_args'][2]['fields'], 'requests terms with object IDs');
test_assert_same('https://example.test/product-category/components/?product_brand=globex', $brands['globex']['url'], 'builds a brand filter URL');

$_GET['product_brand'] = 'acme';
$active_brands = invoke_brand_lookup($plugin, $category);
test_assert_same('https://example.test/product-category/components/', $active_brands['acme']['url'], 'removes the active brand filter URL');

$GLOBALS['test_products'] = [];
$GLOBALS['test_term_calls'] = 0;
$empty_products = invoke_brand_lookup($plugin, $category);
test_assert_same([], $empty_products, 'returns no brands when the category has no products');
test_assert_same(0, $GLOBALS['test_term_calls'], 'skips the term query when there are no products');

$GLOBALS['test_products'] = [101, 102, 103];
$GLOBALS['test_term_error'] = true;
test_assert_same([], invoke_brand_lookup($plugin, $category), 'handles a WordPress term-query error');
$GLOBALS['test_term_error'] = false;

$GLOBALS['test_term_link_error'] = true;
test_assert_same([], invoke_brand_lookup($plugin, $category), 'handles an invalid category URL');
$GLOBALS['test_term_link_error'] = false;

echo "\nAll brand aggregation tests passed.\n";
