# Snow Monkey Heading Widget Area

## Filter hooks

### snow_monkey_heading_widget_area_allow_post_types
```
/**
 * Which post type will display the widget area
 *
 * @param array $post_types
 * @return array
 */
add_filter(
	'snow_monkey_heading_widget_area_allow_post_types',
		function( $post_types ) {
			return $post_types;
		}
	)
```

### snow_monkey_heading_widget_area_view_args
```
/**
 * You can customize the template args
 *
 * @param array $args
 *   @var string $slug
 *   @var string $name
 *   @var array $vars
 * @return array
 */
add_filter(
	'snow_monkey_heading_widget_area_view_args',
	function( $args ) {
		return $args;
	}
);
```

### snow_monkey_heading_widget_area_view_hierarchy
```
/**
 * You can customize the template root
 *
 * @param array $hierarchy
 * @param string $slug
 * @param string $name
 * @param array $vars
 * @return array
 */
add_filter(
	'snow_monkey_heading_widget_area_view_hierarchy',
	function( $hierarchy, $slug, $name, $vars ) {
		return $hierarchy;
	},
	10,
	4
);
```

### snow_monkey_heading_widget_area_view_render
```
/**
 * You can customize the template html
 *
 * @param string $html
 * @param string $slug
 * @param string $name
 * @param array $vars
 * @return string
 */
add_filter(
	'snow_monkey_heading_widget_area_view_render',
	function( $html, $slug, $name, $vars ) {
		return $html;
	},
	10,
	4
);
```

## Action hooks

### snow_monkey_heading_widget_area_view_<template_slug>
```
/**
 * You can customize the template html
 *
 * @param string $name
 * @param array $vars
 * @return string
 */
add_action(
	'snow_monkey_heading_widget_area_view_<template_slug>',
	function( $name, $vars ) {
		?>
		foo
		<?php
	},
	10,
	3
);
```

### snow_monkey_heading_widget_area_view_pre_render
```
/**
 * You can customize the template args
 *
 * @param array $args
 *   @var string $slug
 *   @var string $name
 *   @var array $vars
 * @return array
 */
add_filter(
	'snow_monkey_heading_widget_area_view_pre_render',
	function( $args ) {
		return $args;
	}
);
```

### snow_monkey_heading_widget_area_view_post_render
```
/**
 * You can customize the template args
 *
 * @param array $args
 *   @var string $slug
 *   @var string $name
 *   @var array $vars
 * @return array
 */
add_filter(
	'snow_monkey_heading_widget_area_view_post_render',
	function( $args ) {
		return $args;
	}
);
```
