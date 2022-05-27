<?php
/**
 * Template functions
 * 
 * @package   Backdrop
 * @author    Benjamin Lu ( benlumia007@gmail.com )
 * @copyright Copyright (C) 2019-2021. Benjamin Lu
 * @license   https://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Define namespace
 */
namespace Benlumia007\Backdrop\Template\Hierarchy;

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
