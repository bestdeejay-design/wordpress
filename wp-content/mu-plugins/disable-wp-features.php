<?php
/**
 * Plugin Name: Disable WordPress Core Features
 */

// Dashboard widgets
add_action("wp_dashboard_setup", function () {
    remove_meta_box("dashboard_primary", "dashboard", "side");       // WordPress Events & News
    remove_meta_box("dashboard_secondary", "dashboard", "side");      // WordPress Blog
    remove_meta_box("dashboard_plugins", "dashboard", "normal");      // Plugin updates
    remove_meta_box("dashboard_site_health", "dashboard", "normal");  // Site Health
});

// Disable emoji
remove_action("wp_head", "print_emoji_detection_script", 7);
remove_action("wp_print_styles", "print_emoji_styles");
remove_action("admin_print_scripts", "print_emoji_detection_script");
remove_action("admin_print_styles", "print_emoji_styles");

// Disable embeds
remove_action("wp_head", "wp_oembed_add_discovery_links");
remove_action("wp_head", "wp_oembed_add_host_js");
remove_action("rest_api_init", "wp_oembed_register_route");

// Disable XML-RPC
add_filter("xmlrpc_enabled", "__return_false");

// Disable self pingback
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

// Disable automatic updates screen
add_action("admin_menu", function () {
    remove_submenu_page("index.php", "update-core.php");
});

// Disable block directory
remove_action("enqueue_block_editor_assets", "wp_enqueue_editor_block_directory_assets");
remove_action("enqueue_block_editor_assets", "gutenberg_enqueue_block_editor_assets");

// Disable WordPress news dashboard widget
add_filter("dashboard_secondary_items", "__return_false");
add_action("admin_init", function () {
    remove_action("wp_version_check", "wp_version_check");
    remove_action("admin_init", "_maybe_update_core");
    remove_action("admin_init", "_maybe_update_plugins");
    remove_action("admin_init", "_maybe_update_themes");
});
