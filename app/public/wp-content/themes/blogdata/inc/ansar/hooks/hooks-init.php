<?php
/**
 * Index and Archive Main content.
 */
require get_template_directory().'/inc/ansar/hooks/hook-index-main.php';
/**
 * Header Menu section.
 */
require get_template_directory().'/inc/ansar/hooks/hook-header-type-section.php';

/**
 * Header section.
 */
require get_template_directory().'/inc/ansar/hooks/blocks/header/header-one.php';

/**
 * Side Menu section.
 */
require get_template_directory().'/inc/ansar/hooks/hook-side-menu-section.php';

/**
 * Slider section.
 */
require get_template_directory().'/inc/ansar/hooks/blocks/slider/slider-default.php'; 

/**
 * Header section.
 */
require get_template_directory().'/inc/ansar/hooks/hook-footer-section.php';

/**
 * Logo section.
 */
require get_template_directory().'/inc/ansar/hooks/hook-header-logo-section.php';

/**
 * Menu section.
 */
require get_template_directory().'/inc/ansar/hooks/hook-header-menu-section.php';
require get_template_directory().'/inc/ansar/hooks/hook-header-right-menu-section.php';

/**
 * ticker additions.
 */
require get_template_directory().'/inc/ansar/hooks/hook-front-page-banner-promotions.php';

/**
 * banner additions.
 */
require get_template_directory().'/inc/ansar/hooks/hook-front-page-main-banner-section.php';

/**
 * Missed Footer Section.
 */
require get_template_directory().'/inc/ansar/hooks/hook-footer-missed-posts.php';

/**
 * Single Page
 */
require get_template_directory().'/inc/ansar/hooks/hook-single-page.php';