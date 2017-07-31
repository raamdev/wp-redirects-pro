<?php
/**
 * Template.
 *
 * @author @jaswsinc
 * @copyright WP Sharks™
 */
declare(strict_types=1);
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
$Form = $this->s::menuPageForm('§save-options');
?>
<?= $Form->openTag(); ?>
    <?= $Form->openTable(
        __('General Options', 'wp-redirects'),
        sprintf(__('You can browse our <a href="%1$s" target="_blank">knowledge base</a> to learn more about these options.', 'wp-redirects'), esc_url(s::brandUrl('/kb')))
    ); ?>

        <?= $Form->inputRow([
            'label' => __('Rewrite Prefix', 'wp-redirects'),
            'tip'   => __('This establishes a slug prefix that appears in front of all Redirect permalinks.<hr /><code>[a-z0-9\-]+</code> only.', 'wp-redirects'),
            'note'  => sprintf(__('%1$s/<code>%2$s</code>/my-redirect', 'wp-redirects'), esc_html($this->Wp->home_url), esc_html(s::getOption('rewrite_prefix') ?: s::getDefaultOption('rewrite_prefix'))),

            'name'    => 'rewrite_prefix',
            'value'   => s::getOption('rewrite_prefix'),

            'placeholder' => s::getDefaultOption('rewrite_prefix'),
            'attrs'       => 'pattern="[a-z0-9\-]+" title="[a-z0-9\-]+"',
        ]); ?>

        <?= $Form->selectRow([
            'label' => __('Record Stats?', 'wp-redirects'),
            'tip'   => __('Do you want to record the number of hits for each Redirect, and the last time it was accessed?', 'wp-redirects'),
            'note'  => __('Keep track of Redirect hits &amp; last access time?', 'wp-redirects'),

            'name'    => 'stats_enable',
            'value'   => s::getOption('stats_enable'),
            'options' => [
                '1' => __('Yes', 'wp-redirects'),
                '0' => __('No', 'wp-redirects'),
            ],
        ]); ?>

    <?= $Form->closeTable(); ?>

    <?= $Form->submitButton(); ?>
<?= $Form->closeTag(); ?>
