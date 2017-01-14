<?php
/**
 * Template.
 *
 * @author @jaswsinc
 * @copyright WP Sharks™
 */
declare (strict_types = 1);
namespace WebSharks\WpSharks\WPRedirects\Pro\Classes;

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

if (!defined('WPINC')) {
    exit('Do NOT access this file directly.');
}
extract($this->vars); // Template variables.
$Form = $this->s::postMetaBoxForm('redirect-data');
?>
<?= $Form->openTable(); ?>

    <?= $Form->inputRow([
        'type'  => 'url',
        'label' => __('Redirects To', 'wp-redirects'),
        'tip'   => __('This is where a visitor will be redirected to.', 'wp-redirects'),

        'name'  => '_url',
        'value' => s::getPostMeta($post_id, '_url'),
    ]); ?>

    <?= $Form->selectRow([
        'label' => __('Redirect Status Code', 'wp-redirects'),
        'tip'   => __('This is the status code used in the redirection.', 'wp-redirects'),

        'name'    => '_status_code',
        'value'   => s::getPostMeta($post_id, '_status_code', 'default'),
        'options' => [
            'default' => __('default', 'wp-redirects'),
            '301'     => __('301', 'wp-redirects'),
            '302'     => __('302', 'wp-redirects'),
        ],
    ]); ?>

<?= $Form->closeTable(); ?>
