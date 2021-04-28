<?php
/**
 * @package snow-monkey-heading-widget-area
 * @author inc2734
 * @license GPL-2.0+
 */
?>

<div class="l-heading-widget-area"
	aria-hidden="true"
	data-is-slim-widget-area="false"
	data-is-content-widget-area="false"
	>
	<?php
	// phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UndefinedVariable
	dynamic_sidebar( $sidebar_id );
	// phpcs:enable
	?>
</div>
