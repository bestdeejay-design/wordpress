<?php

// Remove WP generator tag — multiple layers for redundancy
remove_action("wp_head", "wp_generator");
remove_action("wp_head", "wlwmanifest_link");
remove_action("wp_head", "rsd_link");
remove_action("wp_head", "wp_shortlink_wp_head");
remove_action("wp_head", "rest_output_link_wp_head");
remove_action("wp_head", "wp_oembed_add_discovery_links");
remove_action("wp_head", "wp_oembed_add_host_js");
remove_action("wp_head", "feed_links", 2);
remove_action("wp_head", "feed_links_extra", 3);
remove_action("wp_head", "print_emoji_detection_script", 7);
remove_action("wp_print_styles", "print_emoji_styles");
remove_action("admin_print_scripts", "print_emoji_detection_script");
remove_action("admin_print_styles", "print_emoji_styles");
remove_action("rest_api_init", "wp_oembed_register_route");
add_filter("the_generator", "__return_empty_string");

// Strip remaining WP head tags via output buffer (early capture)
add_action("init", function () {
    ob_start(function ($html) {
        $html = preg_replace('/<link rel="https:\/\/api\.w\.org\/".*?\/>/s', "", $html);
        $html = preg_replace('/<link rel="EditURI".*?\/>/s', "", $html);
        $html = preg_replace('/<meta name="generator".*?\/>/s', "", $html);
        return $html;
    });
});

// Disable XML-RPC
add_filter("xmlrpc_enabled", "__return_false");
add_filter("xmlrpc_methods", "__return_empty_array");

// Disable self-pingback
add_action("pre_ping", function (&$links) {
    foreach ($links as $l => $link) {
        if (strpos($link, home_url()) !== false) {
            unset($links[$l]);
        }
    }
});

// Remove recent comments style
add_action("widgets_init", function () {
    global $wp_widget_factory;
    if (isset($wp_widget_factory->widgets["WP_Widget_Recent_Comments"])) {
        remove_action("wp_head", [
            $wp_widget_factory->widgets["WP_Widget_Recent_Comments"],
            "recent_comments_style",
        ]);
    }
});

// Disable automatic updates
add_action("admin_menu", function () {
    remove_submenu_page("index.php", "update-core.php");
});

// Disable block directory
remove_action("enqueue_block_editor_assets", "wp_enqueue_editor_block_directory_assets");

// Disable WP version check & update hooks
add_action("admin_init", function () {
    remove_action("wp_version_check", "wp_version_check");
    remove_action("admin_init", "_maybe_update_core");
    remove_action("admin_init", "_maybe_update_plugins");
    remove_action("admin_init", "_maybe_update_themes");
});

// Remove WP version from scripts & styles
add_filter("style_loader_src", "remove_wp_version_from_url", 9999);
add_filter("script_loader_src", "remove_wp_version_from_url", 9999);
function remove_wp_version_from_url($src) {
    if ($src && strpos($src, "ver=") !== false) {
        return remove_query_arg("ver", $src);
    }
    return $src;
}

// Disable all feeds (RSS, Atom, RDF)
add_action("init", function () {
    remove_action("wp_head", "feed_links", 2);
    remove_action("wp_head", "feed_links_extra", 3);
    add_action("do_feed", "disable_feed", 1);
    add_action("do_feed_rdf", "disable_feed", 1);
    add_action("do_feed_rss", "disable_feed", 1);
    add_action("do_feed_rss2", "disable_feed", 1);
    add_action("do_feed_atom", "disable_feed", 1);
    add_action("do_feed_rss2_comments", "disable_feed", 1);
    add_action("do_feed_atom_comments", "disable_feed", 1);
});
function disable_feed() {
    wp_die("Feed disabled.", "", ["response" => 404]);
}

// Restrict REST API - block anonymous writes, allow reads + media uploads
add_filter("rest_authentication_errors", function ($result) {
    if (!empty($result)) {
        return $result;
    }
    if (!is_user_logged_in()) {
        $route = $GLOBALS["wp"]->query_vars["rest_route"] ?? $_SERVER["REQUEST_URI"] ?? "";
        $method = $_SERVER["REQUEST_METHOD"] ?? "GET";
        if (in_array($method, ["POST", "PUT", "PATCH", "DELETE"])) {
            return new WP_Error(
                "rest_not_logged_in",
                "Требуется авторизация.",
                ["status" => 401]
            );
        }
    }
    return $result;
});

// Disable Heartbeat API for frontend
add_action("init", function () {
    if (!is_admin()) {
        wp_deregister_script("heartbeat");
    }
});

// Disable search
add_action("parse_query", function ($wp_query) {
    if (!is_admin() && $wp_query->is_search) {
        $wp_query->set_404();
    }
});

// Disable trackbacks/pingbacks
add_filter("pings_open", "__return_false");

// Disable comments globally
add_filter("comments_open", "__return_false", 20, 2);
add_filter("pings_open", "__return_false", 20, 2);
add_action("admin_menu", function () {
    remove_menu_page("edit-comments.php");
});
add_action("admin_init", function () {
    remove_meta_box("dashboard_recent_comments", "dashboard", "normal");
});
add_action("wp_dashboard_setup", function () {
    remove_meta_box("dashboard_primary", "dashboard", "side");
    remove_meta_box("dashboard_secondary", "dashboard", "side");
    remove_meta_box("dashboard_plugins", "dashboard", "normal");
    remove_meta_box("dashboard_site_health", "dashboard", "normal");
});
add_filter("dashboard_secondary_items", "__return_false");

// Disable all external HTTP requests explicitly
add_filter("http_request_args", function ($args, $url) {
    $host = parse_url($url, PHP_URL_HOST);
    if ($host && $host !== "localhost" && $host !== "127.0.0.1") {
        $args["timeout"] = 0.01;
        $args["blocking"] = false;
    }
    return $args;
}, 999, 2);

// Disable admin toolbar on frontend
add_filter("show_admin_bar", "__return_false");

// Set max upload size to 32MB
add_filter("upload_size_limit", function ($size) {
    return 32 * MB_IN_BYTES;
});

// Disable big image scaling entirely (prevents memory issues with large PNGs)
add_filter("big_image_size_threshold", "__return_false");

// Strip remaining WP head tags via output buffer (early capture)
add_action("send_headers", function () {
    ob_start(function ($html) {
        $count = 0;
        $html = str_replace(
            ['<meta name="generator" content="WordPress 7.0" />', '<link rel="https://api.w.org/" href="https://85.143.101.97:8443/wp-json/" />', '<link rel="EditURI" type="application/rsd+xml" title="RSD" href="https://85.143.101.97:8443/xmlrpc.php?rsd" />'],
            "",
            $html,
            $count
        );
        return $html;
    });
});
