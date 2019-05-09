'use strict';

import forEachHtmlNodes from '@inc2734/for-each-html-nodes';

const moveWidgetArea = (widgetArea, firstHeading) => {
  if (firstHeading) {
    firstHeading.parentNode.insertBefore(widgetArea, firstHeading);
  }

  const ariaHidden = firstHeading ? 'false' : 'true';
  widgetArea.setAttribute('aria-hidden', ariaHidden);
};

window.addEventListener(
  'DOMContentLoaded',
  () => {
    const firstHeading = document.querySelector('.c-entry__content > h2');
    const widgetAreas = document.getElementsByClassName('l-heading-widget-area');
    forEachHtmlNodes(widgetAreas, (element) => moveWidgetArea(element, firstHeading));
  },
  false
);
