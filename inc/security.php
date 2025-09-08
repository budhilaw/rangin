<?php
/**
 * Security Integrations (Cloudflare Turnstile)
 *
 * @package PersonalWebsite
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Helpers to read options
function personal_website_turnstile_enabled() {
    return (bool) get_option('enable_turnstile', false);
}

function personal_website_turnstile_site_key() {
    return trim((string) get_option('turnstile_site_key', ''));
}

function personal_website_turnstile_secret_key() {
    return trim((string) get_option('turnstile_secret_key', ''));
}

/**
 * Preferred Turnstile theme.
 * Allowed values: light | dark | auto
 * Default: light (forces light mode regardless of system theme)
 */
function personal_website_turnstile_theme() {
    $val = (string) get_option('turnstile_theme', 'auto');
    $allowed = array('light', 'dark', 'auto');
    return in_array($val, $allowed, true) ? $val : 'light';
}

/**
 * Enqueue Cloudflare Turnstile script on front-end where comments are used.
 */
function personal_website_enqueue_turnstile_front() {
    if (!personal_website_turnstile_enabled()) { return; }

    $site_key = personal_website_turnstile_site_key();
    $secret   = personal_website_turnstile_secret_key();
    if ($site_key === '' || $secret === '') { return; }

    if (is_singular() && comments_open()) {
        wp_enqueue_script('cf-turnstile', 'https://challenges.cloudflare.com/turnstile/v0/api.js', array(), null, true);
    }
}
add_action('wp_enqueue_scripts', 'personal_website_enqueue_turnstile_front');

/**
 * Add async/defer to Turnstile script for performance.
 */
function personal_website_turnstile_script_attrs($tag, $handle, $src) {
    if ($handle === 'cf-turnstile') {
        $tag = str_replace('<script ', '<script async defer ', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'personal_website_turnstile_script_attrs', 10, 3);

/**
 * Enqueue and render Turnstile on the login page.
 */
function personal_website_enqueue_turnstile_login() {
    if (!personal_website_turnstile_enabled()) { return; }

    $site_key = personal_website_turnstile_site_key();
    $secret   = personal_website_turnstile_secret_key();
    if ($site_key === '' || $secret === '') { return; }

    wp_enqueue_script('cf-turnstile', 'https://challenges.cloudflare.com/turnstile/v0/api.js', array(), null, true);
}
add_action('login_enqueue_scripts', 'personal_website_enqueue_turnstile_login');

function personal_website_render_turnstile_login() {
    if (!personal_website_turnstile_enabled()) { return; }
    $site_key = personal_website_turnstile_site_key();
    $secret   = personal_website_turnstile_secret_key();
    if ($site_key === '' || $secret === '') { return; }

    echo '<div class="cf-turnstile" data-sitekey="' . esc_attr($site_key) . '" data-theme="light"></div>';
}
add_action('login_form', 'personal_website_render_turnstile_login');

/**
 * Render Turnstile widget in comment forms (for guests and logged in users).
 */
function personal_website_render_turnstile_comment_fields() {
    if (!personal_website_turnstile_enabled()) { return; }
    $site_key = personal_website_turnstile_site_key();
    $secret   = personal_website_turnstile_secret_key();
    if ($site_key === '' || $secret === '') { return; }

    echo '<div class="cf-turnstile" data-sitekey="' . esc_attr($site_key) . '" data-theme="' . esc_attr(personal_website_turnstile_theme()) . '" style="margin: 12px 0;"></div>';
}
add_action('comment_form_after_fields', 'personal_website_render_turnstile_comment_fields');
add_action('comment_form_logged_in_after', 'personal_website_render_turnstile_comment_fields');

/**
 * Verify Turnstile token server-side.
 */
function personal_website_turnstile_verify_request() {
    $secret = personal_website_turnstile_secret_key();
    if ($secret === '') { return false; }

    $token = isset($_POST['cf-turnstile-response']) ? sanitize_text_field(wp_unslash($_POST['cf-turnstile-response'])) : '';
    if ($token === '') { return false; }

    $response = wp_remote_post('https://challenges.cloudflare.com/turnstile/v0/siteverify', array(
        'timeout' => 8,
        'body'    => array(
            'secret'   => $secret,
            'response' => $token,
            'remoteip' => isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field($_SERVER['REMOTE_ADDR']) : '',
        ),
    ));

    if (is_wp_error($response)) { return false; }

    $code = (int) wp_remote_retrieve_response_code($response);
    if ($code < 200 || $code >= 300) { return false; }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);
    if (!is_array($data)) { return false; }

    return !empty($data['success']);
}

/**
 * Enforce Turnstile on login authentication.
 */
function personal_website_turnstile_authenticate($user, $username, $password) {
    if (!personal_website_turnstile_enabled()) { return $user; }

    // Only enforce on actual login POST requests
    if (wp_doing_ajax()) { return $user; }
    if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') { return $user; }

    $site_key = personal_website_turnstile_site_key();
    $secret   = personal_website_turnstile_secret_key();
    if ($site_key === '' || $secret === '') { return $user; }

    $ok = personal_website_turnstile_verify_request();
    if (!$ok) {
        return new WP_Error('turnstile_error', __('Cloudflare Turnstile verification failed. Please try again.', 'personal-website'));
    }

    return $user;
}
add_filter('authenticate', 'personal_website_turnstile_authenticate', 20, 3);

/**
 * Enforce Turnstile on comment submissions.
 */
function personal_website_turnstile_preprocess_comment($commentdata) {
    if (!personal_website_turnstile_enabled()) { return $commentdata; }

    // Only check when posting a comment
    if (strtoupper($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') { return $commentdata; }

    $site_key = personal_website_turnstile_site_key();
    $secret   = personal_website_turnstile_secret_key();
    if ($site_key === '' || $secret === '') { return $commentdata; }

    $ok = personal_website_turnstile_verify_request();
    if (!$ok) {
        wp_die(__('Cloudflare Turnstile verification failed. Please go back and try again.', 'personal-website'));
    }

    return $commentdata;
}
add_filter('preprocess_comment', 'personal_website_turnstile_preprocess_comment');
