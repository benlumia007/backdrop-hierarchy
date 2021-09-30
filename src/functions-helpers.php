<?php
/**
 * Backdrop Core ( src/Template/functions-template.php )
 *
 * @package   Backdrop Core
 * @copyright Copyright (C) 2019-2021. Benjamin Lu
 * @author    Benjamin Lu ( https://getbenonit.com )
 * @license   https://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Define namespace
 */
namespace Benlumia007\Backdrop\Template;

/**
 * Return the relative path to where templates are held in the theme
 * 
 * @since  3.0.0
 * @access public
 * @param  string $file 
 * @return string
 */
function path( $file = '' ) {
	$file = ltrim( $file, '/' );
	$path = apply_filters( 'backdrop/template/path', 'resources/views' );

	return $file ? trailingslashit( $path ) . $file : trailingslashit( $path );
}