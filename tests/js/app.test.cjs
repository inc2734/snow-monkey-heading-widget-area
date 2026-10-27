const assert = require( 'node:assert/strict' );
const { readFileSync } = require( 'node:fs' );
const { resolve } = require( 'node:path' );
const { test } = require( 'node:test' );
const { JSDOM } = require( 'jsdom' );

const bundle = readFileSync(
	resolve( __dirname, '../../dist/js/app.js' ),
	'utf8'
);

function render( t, content, widgets ) {
	const dom = new JSDOM(
		`<main><div class="c-entry__content">${ content }</div>${ widgets }</main>`,
		{ runScripts: 'outside-only' }
	);
	t.after( () => dom.window.close() );
	dom.window.eval( bundle );
	dom.window.dispatchEvent( new dom.window.Event( 'DOMContentLoaded' ) );
	return dom.window.document;
}

const widget =
	'<aside class="l-heading-widget-area" aria-hidden="true">Widget</aside>';

test( 'moves the widget immediately before the first top-level h2', ( t ) => {
	const document = render(
		t,
		'<p>Intro</p><h2>First</h2><h2>Second</h2>',
		widget
	);
	const heading = document.querySelector( 'h2' );
	const area = document.querySelector( '.l-heading-widget-area' );
	assert.equal( heading.previousElementSibling, area );
	assert.equal( area.getAttribute( 'aria-hidden' ), 'false' );
	assert.equal( area.textContent, 'Widget' );
} );

test( 'keeps the widget hidden when there is no heading', ( t ) => {
	const document = render( t, '<p>Content</p>', widget );
	const area = document.querySelector( '.l-heading-widget-area' );
	assert.equal( area.parentElement.tagName, 'MAIN' );
	assert.equal( area.getAttribute( 'aria-hidden' ), 'true' );
} );

test( 'ignores nested h2 headings and other heading levels', ( t ) => {
	const document = render(
		t,
		'<h3>Subheading</h3><section><h2>Nested</h2></section>',
		widget
	);
	const area = document.querySelector( '.l-heading-widget-area' );
	assert.equal( area.parentElement.tagName, 'MAIN' );
	assert.equal( area.getAttribute( 'aria-hidden' ), 'true' );
} );

test( 'moves multiple widgets in their original order', ( t ) => {
	const document = render(
		t,
		'<h2>First</h2>',
		widget + widget.replace( 'Widget', 'Second' )
	);
	const areas = document.querySelectorAll( '.l-heading-widget-area' );
	assert.equal( areas.length, 2 );
	assert.equal( areas[ 0 ].nextElementSibling, areas[ 1 ] );
	assert.equal(
		areas[ 1 ].nextElementSibling,
		document.querySelector( 'h2' )
	);
	for ( const area of areas ) {
		assert.equal( area.getAttribute( 'aria-hidden' ), 'false' );
	}
} );

test( 'handles pages without a widget area', ( t ) => {
	const document = render( t, '<h2>First</h2>', '' );
	assert.equal(
		document.querySelector( '.c-entry__content' ).children.length,
		1
	);
	assert.equal( document.querySelector( '.l-heading-widget-area' ), null );
} );
