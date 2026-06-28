<?php
if (is_front_page() && is_home()) {
    get_template_part('front-page');
} elseif (is_front_page()) {
    get_template_part('front-page');
} elseif (is_home() || is_archive()) {
    get_template_part('archive');
} elseif (is_singular()) {
    if (is_page()) {
        get_template_part('page');
    } else {
        get_template_part('single');
    }
} else {
    get_template_part('page');
}
