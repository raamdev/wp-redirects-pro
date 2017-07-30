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
extract($this->vars); // Template variables.
$Form = $this->s::postMetaBoxForm('redirect-data');

switch (s::getOption('regex_tests')) {
    case 'url':
        $regex_tests = __('full URL (<code>scheme://host/path/?query</code>)', 'wp-redirects');
        break;

    case 'request_uri':
        $regex_tests = __('request URI (<code>/path/?query</code>)', 'wp-redirects');
        break;

    case 'path':
    default: // Default case.
        $regex_tests = __('path only (i.e. <code>/path</code>, no trailing slash &amp; no query)', 'wp-redirects');
        break;
}
?>
<?= $Form->openTable(); ?>

    <?= $Form->inputRow([
        'type'  => 'number',
        'label' => __('Status Code', 'wp-redirects'),
        'tip'   => __('HTTP status code.', 'wp-redirects'),

        'name'  => '_code',
        'value' => s::getPostMeta($post_id, '_code', s::getOption('default_code')),
    ]); ?>

    <?= $Form->inputRow([
        'label' => __('Redirect To', 'wp-redirects'),
        'tip'   => __('This is where a visitor will be redirected to.', 'wp-redirects'),

        'name'  => '_url',
        'value' => s::getPostMeta($post_id, '_url'),
    ]); ?>

    <?= $Form->hrRow(); ?>

    <?= $Form->selectRow([
        'label' => __('Force Top?', 'wp-redirects'),
        'tip'   => __('If enabled, this breaks out of frames; i.e., when redirecting, the new URL is always loaded as the top-level document.', 'wp-redirects'),

        'name'    => '_top',
        'value'   => s::getPostMeta($post_id, '_top', s::getOption('default_top')),
        'options' => [
            '0' => __('No', 'wp-redirects'),
            '1' => __('Yes', 'wp-redirects'),
        ],
    ]); ?>

    <?= $Form->selectRow([
        'label' => __('Cacheable?', 'wp-redirects'),
        'tip'   => __('Allow a browser to cache the redirection and therefore bypass the original URL on future requests?', 'wp-redirects'),

        'name'    => '_cacheable',
        'value'   => s::getPostMeta($post_id, '_cacheable', s::getOption('default_cacheable')),
        'options' => [
            '0' => __('No', 'wp-redirects'),
            '1' => __('Yes', 'wp-redirects'),
        ],
    ]); ?>

    <?= $Form->selectRow([
        'label' => __('Forward Query?', 'wp-redirects'),
        'tip'   => __('Pass query string arguments to new URL?', 'wp-redirects'),

        'name'    => '_forward_query',
        'value'   => s::getPostMeta($post_id, '_forward_query', s::getOption('default_forward_query')),
        'options' => [
            '0' => __('No', 'wp-redirects'),
            '1' => __('Yes', 'wp-redirects'),
        ],
    ]); ?>

    <?= $Form->hrRow(); ?>

    <?= $Form->inputRow([
        'label' => __('Regex Pattern', 'wp-redirects'),
        'tip'   => __('Optionally match any location served by PHP/WordPress; i.e., if any location matches a pattern entered here, it will trigger this redirect.', 'wp-redirects'),
        'note'  => sprintf(__('<code>/^</code><strong>pattern</strong><code>$/ui</code> &nbsp;&nbsp; <em>Enter pattern only, excluding <code>/^</code> and <code>$/ui</code> delimiters.<br />You\'re testing the %1$s from beginning to end.</em>', 'wp-redirects'), $regex_tests),

        'name'  => '_regex',
        'value' => s::getPostMeta($post_id, '_regex'),

        'class' => '-monospace',
        'attrs' => 'spellcheck="false"',
    ]); ?>

<?= $Form->closeTable(); ?>
