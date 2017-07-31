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
        __('Redirect Defaults', 'wp-redirects'),
        sprintf(__('You can browse our <a href="%1$s" target="_blank">knowledge base</a> to learn more about these options.', 'wp-redirects'), esc_url(s::brandUrl('/kb')))
    ); ?>

        <?= $Form->inputRow([
            'type'  => 'number',
            'label' => __('Status Code', 'wp-redirects'),
            'tip'   => __('This determines the default HTTP redirect status code; i.e., what is already filled-in when creating a new Redirect.', 'wp-redirects'),

            'name'  => 'default_code',
            'value' => s::getOption('default_code'),
        ]); ?>

        <?= $Form->selectRow([
            'label' => __('Force Top', 'wp-redirects'),
            'tip'   => __('This determines the default value for the Force Top option; i.e., what is already filled-in when creating a new Redirect.', 'wp-redirects'),

            'name'    => 'default_top',
            'value'   => s::getOption('default_top'),
            'options' => [
                '0' => __('No', 'wp-redirects'),
                '1' => __('Yes', 'wp-redirects'),
            ],
        ]); ?>

        <?= $Form->selectRow([
            'label' => __('Cacheable', 'wp-redirects'),
            'tip'   => __('This determines the default value for the Cacheable option; i.e., what is already filled-in when creating a new Redirect.', 'wp-redirects'),

            'name'    => 'default_cacheable',
            'value'   => s::getOption('default_cacheable'),
            'options' => [
                '0' => __('No', 'wp-redirects'),
                '1' => __('Yes', 'wp-redirects'),
            ],
        ]); ?>

        <?= $Form->selectRow([
            'label' => __('Forward Query', 'wp-redirects'),
            'tip'   => __('This determines the default value for the Forward Query option; i.e., what is already filled-in when creating a new Redirect.', 'wp-redirects'),

            'name'    => 'default_forward_query',
            'value'   => s::getOption('default_forward_query'),
            'options' => [
                '0' => __('No', 'wp-redirects'),
                '1' => __('Yes', 'wp-redirects'),
            ],
        ]); ?>

    <?= $Form->closeTable(); ?>

    <?= $Form->submitButton(); ?>
<?= $Form->closeTag(); ?>
