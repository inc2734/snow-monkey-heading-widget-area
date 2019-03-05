'use strict';

import forEachHtmlNodes from '@inc2734/for-each-html-nodes';

const moveWidgetArea = (widgetArea, firstHeading) => {
  firstHeading.parentNode.insertBefore(widgetArea, firstHeading);
  widgetArea.setAttribute('aria-hidden', 'false');
};

window.addEventListener(
  'DOMContentLoaded',
  () => {
    const firstHeading = document.querySelector('.c-entry__content > h2');
    if (! firstHeading) {
      return;
    }

    const widgetAreas = document.getElementsByClassName('l-heading-widget-area');

    forEachHtmlNodes(widgetAreas, (element) => moveWidgetArea(element, firstHeading));
  },
  false
);
