

<?php
/**
 * Template functions.
 *
 * Helper functions and template tags related to templates.
 *
 * @package   HybridCore
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2008 - 2021, Justin Tadlock
 * @link      https://themehybrid.com/hybrid-core
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Backdrop\Template\Hierarchy;

use Backdrop\Template\Hierarchy\Contracts\Hierarchy;
use Backdrop\Proxies\App;

if ( ! function_exists( __NAMESPACE__ . '\\hierarchy' ) ) {
    /**
     * Returns the global hierarchy. This is a wrapper around the values
     * stored via the template hierarchy object.
     *
     * @since  1.0.0
     * @access public
     * @return array
     */
    function hierarchy() {
        return apply_filters(
            'hybrid/template/hierarchy',
            App::resolve( Hierarchy::class )->hierarchy()
        );
    }
}