<?php
/**
 * Post type utils.
 *
 * @author @jaswsinc
 * @copyright WP Sharks™
 */
declare(strict_types=1);
namespace WebSharks\WpSharks\WPRedirects\Pro\Classes\Utils;

use WebSharks\WpSharks\WPRedirects\Pro\Classes;
use WebSharks\WpSharks\WPRedirects\Pro\Interfaces;
use WebSharks\WpSharks\WPRedirects\Pro\Traits;
#
use WebSharks\WpSharks\WPRedirects\Pro\Classes\AppFacades as a;
use WebSharks\WpSharks\WPRedirects\Pro\Classes\SCoreFacades as s;
use WebSharks\WpSharks\WPRedirects\Pro\Classes\CoreFacades as c;
#
use WebSharks\WpSharks\Core\Classes as SCoreClasses;
use WebSharks\WpSharks\Core\Interfaces as SCoreInterfaces;
use WebSharks\WpSharks\Core\Traits as SCoreTraits;
#
use WebSharks\Core\WpSharksCore\Classes as CoreClasses;
use WebSharks\Core\WpSharksCore\Classes\Core\Base\Exception;
use WebSharks\Core\WpSharksCore\Interfaces as CoreInterfaces;
use WebSharks\Core\WpSharksCore\Traits as CoreTraits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Post type utils.
 *
 * @since 170730.42995 Initial release.
 */
class PostType extends SCoreClasses\SCore\Base\Core
{
    /**
     * Capabilities.
     *
     * @since 170730.42995
     *
     * @type array Caps.
     */
    public $caps;

    /**
     * Constructor.
     *
     * @since 170730.42995 Initial release.
     *
     * @param Classes\App $App Application.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        $this->caps = [
            'create_redirects',

            'edit_redirects',
            'edit_others_redirects',
            'edit_published_redirects',
            'edit_private_redirects',

            'publish_redirects',

            'delete_redirects',
            'delete_private_redirects',
            'delete_published_redirects',
            'delete_others_redirects',

            'read_private_redirects',
        ];
        $this->caps = s::applyFilters('caps', $this->caps);
    }

    /**
     * On `init` hook.
     *
     * @since 170730.42995 Initial release.
     */
    public function onInit()
    {
        register_post_type(
            'redirect',
            s::applyFilters('post_type_args', [
                'public' => true,

                'supports' => [
                    'title',
                    'author',
                ],
                'rewrite' => [
                    'with_front' => false,
                    'slug'       => s::getOption('rewrite_prefix')
                        ?: s::getDefaultOption('rewrite_prefix'),
                ],
                'menu_position' => null,
                'menu_icon'     => 'dashicons-admin-links',

                'description' => __('Redirections.', 'wp-redirects'),

                'labels' => [ // See: <http://jas.xyz/244m2Sd>
                    'name'          => __('Redirects', 'wp-redirects'),
                    'singular_name' => __('Redirect', 'wp-redirects'),

                    'name_admin_bar' => __('Redirect', 'wp-redirects'),
                    'menu_name'      => __('Redirects', 'wp-redirects'),

                    'all_items'    => __('All Redirects', 'wp-redirects'),
                    'add_new'      => __('Add Redirect', 'wp-redirects'),
                    'add_new_item' => __('Add New Redirect', 'wp-redirects'),
                    'new_item'     => __('New Redirect', 'wp-redirects'),
                    'edit_item'    => __('Edit Redirect', 'wp-redirects'),
                    'view_item'    => __('View Redirect', 'wp-redirects'),

                    'search_items'       => __('Search Redirects', 'wp-redirects'),
                    'not_found'          => __('No Redirects Found', 'wp-redirects'),
                    'not_found_in_trash' => __('No Redirects Found in Trash', 'wp-redirects'),

                    'insert_into_item'      => __('Insert Into Redirect', 'wp-redirects'),
                    'uploaded_to_this_item' => __('Upload to this Redirect', 'wp-redirects'),

                    'featured_image'        => __('Set Featured Image', 'wp-redirects'),
                    'remove_featured_image' => __('Remove Featured Image', 'wp-redirects'),
                    'use_featured_image'    => __('Use as Featured Image', 'wp-redirects'),

                    'items_list'            => __('Redirects List', 'wp-redirects'),
                    'items_list_navigation' => __('Redirects List Navigation', 'wp-redirects'),

                    'archives'          => __('Redirect Archives', 'wp-redirects'),
                    'filter_items_list' => __('Filter Redirects List', 'wp-redirects'),
                    'parent_item_colon' => __('Parent Redirect:', 'wp-redirects'),
                ],

                'map_meta_cap'    => true,
                'capability_type' => [
                    'redirect',
                    'redirects',
                ],
            ])
        );
        register_taxonomy(
            'redirect_category',
            'redirect',
            s::applyFilters('category_taxonomy_args', [
                'public'             => false,
                'show_ui'            => true,
                'show_in_menu'       => true,
                'show_tagcloud'      => false,
                'show_in_quick_edit' => true,
                'show_admin_column'  => true,
                'hierarchical'       => true,

                'rewrite' => false, // See below.

                'description' => __('Redirect Categories', 'wp-redirects'),

                'labels' => [ // See: <http://jas.xyz/244m1Oc>
                    'name'          => __('Categories', 'wp-redirects'),
                    'singular_name' => __('Category', 'wp-redirects'),

                    'name_admin_bar' => __('Category', 'wp-redirects'),
                    'menu_name'      => __('Categories', 'wp-redirects'),

                    'all_items'           => __('All Categories', 'wp-redirects'),
                    'add_new_item'        => __('Add New Category', 'wp-redirects'),
                    'new_item_name'       => __('New Category Name', 'wp-redirects'),
                    'add_or_remove_items' => __('Add or Remove Categories', 'wp-redirects'),
                    'view_item'           => __('View Category', 'wp-redirects'),
                    'edit_item'           => __('Edit Category', 'wp-redirects'),
                    'update_item'         => __('Update Category', 'wp-redirects'),

                    'search_items' => __('Search Categories', 'wp-redirects'),
                    'not_found'    => __('No Categories Found', 'wp-redirects'),
                    'no_terms'     => __('No Categories', 'wp-redirects'),

                    'choose_from_most_used'      => __('Choose From the Most Used Categories', 'wp-redirects'),
                    'separate_items_with_commas' => __('Separate Categories w/ Commas', 'wp-redirects'),

                    'items_list'            => __('Categories List', 'wp-redirects'),
                    'items_list_navigation' => __('Categories List Navigation', 'wp-redirects'),

                    'archives'          => __('All Categories', 'wp-redirects'),
                    'popular_items'     => __('Popular Categories', 'wp-redirects'),
                    'parent_item'       => __('Parent Category', 'wp-redirects'),
                    'parent_item_colon' => __('Parent Category:', 'wp-redirects'),
                ],

                'capabilities' => [
                    'assign_terms' => 'edit_redirects',
                    'edit_terms'   => 'edit_redirects',
                    'manage_terms' => 'edit_others_redirects',
                    'delete_terms' => 'delete_others_redirects',
                ],
            ])
        );
    }
}
