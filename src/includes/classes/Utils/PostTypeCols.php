<?php
/**
 * Post type cols.
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
 * Post type cols.
 *
 * @since 170731.4430 Refactor.
 */
class PostTypeCols extends SCoreClasses\SCore\Base\Core
{
    /**
     * On `admin_init` hook.
     *
     * @since 170731.4430 Refactor custom columns.
     */
    public function onAdminInit()
    {
        if (!s::isMenuPageForPostType('/^redirect$/')) {
            return; // Not applicable.
        } elseif (!($user_id = (int) get_current_user_id())) {
            return; // Not possible.
        }
        if (!is_array(get_user_meta($user_id, 'manageedit-redirectcolumnshidden', true))) {
            update_user_meta($user_id, 'manageedit-redirectcolumnshidden', [
                'author', 'date', '_url', '_regex',
            ]);
        }
    }

    /**
     * On `manage_redirect_posts_columns` filter.
     *
     * @since 170731.4430 Refactor custom columns.
     *
     * @param array|mixed $columns Current columns.
     *
     * @return array Filtered columns.
     */
    public function onColumns($columns): array
    {
        $columns = (array) ($columns ?: []);

        $columns['_code']          = __('Status Code', 'wp-redirects');
        $columns['_url']           = __('Redirect To', 'wp-redirects');
        $columns['_top']           = __('Force Top', 'wp-redirects');
        $columns['_cacheable']     = __('Cacheable', 'wp-redirects');
        $columns['_forward_query'] = __('Fwd Query', 'wp-redirects');
        $columns['_regex']         = __('Regex', 'wp-redirects');

        if (s::getOption('stats_enable')) {
            $columns['_hits']        = __('Hits', 'wp-redirects');
            $columns['_last_access'] = __('Last Access', 'wp-redirects');
        }
        return $columns;
    }

    /**
     * On `manage_edit-redirect_sortable_columns` filter.
     *
     * @since 170731.4430 Refactor custom columns.
     *
     * @param array|mixed $columns Current columns.
     *
     * @return array Filtered columns.
     */
    public function onSortableColumns($columns): array
    {
        $columns = (array) ($columns ?: []);

        $columns['_code']          = s::postMetaKey('_code');
        $columns['_url']           = s::postMetaKey('_url');
        $columns['_top']           = s::postMetaKey('_top');
        $columns['_cacheable']     = s::postMetaKey('_cacheable');
        $columns['_forward_query'] = s::postMetaKey('_forward_query');
        $columns['_regex']         = s::postMetaKey('_regex');

        if (s::getOption('stats_enable')) {
            $columns['_hits']        = s::postMetaKey('_hits');
            $columns['_last_access'] = s::postMetaKey('_last_access');
        }
        return $columns;
    }

    /**
     * On `manage_redirect_posts_custom_column` hook.
     *
     * @since 170731.4430 Refactor custom columns.
     *
     * @param string     $column Column key.
     * @param int|string $id     Redirect ID.
     */
    public function onColumnValue($column, $id)
    {
        $id     = (int) $id;
        $column = (string) $column;

        if (s::getOption('stats_enable')) {
            switch ($column) {
                case '_code':
                    echo (string) (int) s::getPostMeta($id, '_code');
                    break;

                case '_url':
                    echo esc_html((string) s::getPostMeta($id, '_url'));
                    break;

                case '_top':
                    echo c::yesNo(s::getPostMeta($id, '_top'));
                    break;

                case '_cacheable':
                    echo c::yesNo(s::getPostMeta($id, '_cacheable'));
                    break;

                case '_forward_query':
                    echo c::yesNo(s::getPostMeta($id, '_forward_query'));
                    break;

                case '_regex':
                    echo esc_html((string) s::getPostMeta($id, '_regex'));
                    break;

                case '_hits':
                    echo (string) (int) s::getPostMeta($id, '_hits');
                    break;

                case '_last_access':
                    if (!($last_access = (int) s::getPostMeta($id, '_last_access'))) {
                        echo __('never', 'wp-redirects');
                    } else {
                        echo sprintf(__('%1$s ago', 'wp-redirects'), human_time_diff(time(), $last_access));
                    }
                    break;
            }
        }
    }

    /**
     * On `pre_get_posts` hook.
     *
     * @since 170731.4430 Refactor custom columns.
     *
     * @param string     $column Column key.
     * @param int|string $id     Redirect ID.
     */
    public function onPreGetPosts(\WP_Query $WP_Query)
    {
        if (!$this->Wp->is_admin) {
            return; // Not applicable.
        }
        if (s::getOption('stats_enable')) {
            switch ($WP_Query->get('orderby')) {
                case s::postMetaKey('_code'):
                    $WP_Query->set('orderby', 'meta_value_num');
                    $WP_Query->set('meta_key', s::postMetaKey('_code'));
                    break;

                case s::postMetaKey('_url'):
                    $WP_Query->set('orderby', 'meta_value');
                    $WP_Query->set('meta_key', s::postMetaKey('_url'));
                    break;

                case s::postMetaKey('_top'):
                    $WP_Query->set('orderby', 'meta_value_num');
                    $WP_Query->set('meta_key', s::postMetaKey('_top'));
                    break;

                case s::postMetaKey('_cacheable'):
                    $WP_Query->set('orderby', 'meta_value_num');
                    $WP_Query->set('meta_key', s::postMetaKey('_cacheable'));
                    break;

                case s::postMetaKey('_forward_query'):
                    $WP_Query->set('orderby', 'meta_value_num');
                    $WP_Query->set('meta_key', s::postMetaKey('_forward_query'));
                    break;

                case s::postMetaKey('_regex'):
                    $WP_Query->set('orderby', 'meta_value');
                    $WP_Query->set('meta_key', s::postMetaKey('_regex'));
                    break;

                case s::postMetaKey('_hits'):
                    $WP_Query->set('orderby', 'meta_value_num');
                    $WP_Query->set('meta_key', s::postMetaKey('_hits'));
                    break;

                case s::postMetaKey('_last_access'):
                    $WP_Query->set('orderby', 'meta_value_num');
                    $WP_Query->set('meta_key', s::postMetaKey('_last_access'));
                    break;
            }
        }
    }
}
