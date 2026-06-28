<?php
/**
 * Plugin Name: Foundation Widgets
 * Description: TOC widget, Partners widget for SPbGTI Foundation theme
 * Version: 1.0
 */

defined("ABSPATH") || exit;

// Table of Contents Widget
class FW_TOC_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct("fw_toc", "Содержание страницы", ["description" => "Показывает содержание текущей страницы"]);
    }
    public function widget($args, $instance) {
        if (!is_singular()) return;
        $post_id = get_the_ID();
        $content = get_post_field("post_content", $post_id);
        if (!$content) return;

        preg_match_all("/<h([1-3])(?:[^>]*)>(.+?)<\/h[1-3]>/si", $content, $matches, PREG_SET_ORDER);
        if (empty($matches)) return;

        echo $args["before_widget"];
        $title = !empty($instance["title"]) ? $instance["title"] : "Содержание";
        echo $args["before_title"] . esc_html($title) . $args["after_title"];

        echo '<nav class="fw-toc"><ul>';
        $prev = 1;
        foreach ($matches as $i => $m) {
            $level = (int)$m[1];
            $text = strip_tags($m[2]);
            $anchor = sanitize_title($text);
            $diff = $level - $prev;
            if ($diff > 0) echo str_repeat('<ul>', $diff);
            elseif ($diff < 0) echo str_repeat('</li></ul>', -$diff) . '</li>';
            elseif ($i > 0) echo '</li>';
            echo '<li class="fw-toc-l' . $level . '"><a href="#' . $anchor . '">' . esc_html($text) . '</a>';
            $prev = $level;
        }
        echo str_repeat('</li></ul>', $prev);
        echo '</nav>';

        echo $args["after_widget"];
    }
    public function form($instance) {
        $title = $instance["title"] ?? "Содержание";
        echo '<p><label>Заголовок: <input type="text" name="' . $this->get_field_name("title") . '" value="' . esc_attr($title) . '" class="widefat"></label></p>';
    }
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance["title"] = sanitize_text_field($new_instance["title"]);
        return $instance;
    }
}

// Partners Widget
class FW_Partners_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct("fw_partners", "Партнёры", ["description" => "Список партнёров фонда"]);
    }
    public function widget($args, $instance) {
        $query = new WP_Query([
            "post_type" => "partner",
            "posts_per_page" => !empty($instance["count"]) ? (int)$instance["count"] : -1,
            "orderby" => "menu_order",
            "order" => "ASC",
        ]);
        if (!$query->have_posts()) return;

        echo $args["before_widget"];
        $title = !empty($instance["title"]) ? $instance["title"] : "Наши партнёры";
        echo $args["before_title"] . esc_html($title) . $args["after_title"];

        echo '<div class="fw-partners">';
        while ($query->have_posts()) {
            $query->the_post();
            $cats = wp_get_post_terms(get_the_ID(), "partner_category");
            $cat_name = $cats ? $cats[0]->name : "";
            echo '<div class="fw-partner">';
            if (has_post_thumbnail()) {
                echo '<div class="fw-partner-logo">' . get_the_post_thumbnail(get_the_ID(), "thumbnail") . '</div>';
            }
            echo '<div class="fw-partner-info">';
            echo '<h4 class="fw-partner-title">' . get_the_title() . '</h4>';
            if ($cat_name) echo '<span class="fw-partner-cat">' . esc_html($cat_name) . '</span>';
            $excerpt = get_the_excerpt();
            if ($excerpt) echo '<p class="fw-partner-desc">' . esc_html($excerpt) . '</p>';
            echo '</div></div>';
        }
        echo '</div>';
        wp_reset_postdata();

        echo $args["after_widget"];
    }
    public function form($instance) {
        $title = $instance["title"] ?? "Наши партнёры";
        $count = $instance["count"] ?? -1;
        echo '<p><label>Заголовок: <input type="text" name="' . $this->get_field_name("title") . '" value="' . esc_attr($title) . '" class="widefat"></label></p>';
        echo '<p><label>Количество (-1 = все): <input type="number" name="' . $this->get_field_name("count") . '" value="' . esc_attr($count) . '" class="widefat"></label></p>';
    }
    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance["title"] = sanitize_text_field($new_instance["title"]);
        $instance["count"] = (int)$new_instance["count"];
        return $instance;
    }
}

add_action("widgets_init", function () {
    register_widget("FW_TOC_Widget");
    register_widget("FW_Partners_Widget");
});
