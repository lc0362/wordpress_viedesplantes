<?php
/**
 * The sidebar containing the main widget area.
 *
 * @package BlogData
 */

if ( ! is_active_sidebar( 'menu-sidebar-content' ) ) {
    return;
} ?>
<div class="bs-widget post">
    <div class="post-inner bs-sidebar ">
        <?php dynamic_sidebar( 'menu-sidebar-content' ); ?>
    </div>
</div>
<?php