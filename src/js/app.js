'use strict';

export default class SnowMonkeyHeadingWidgetArea {
  constructor() {
    window.addEventListener('DOMContentLoaded', () => this._DOMContentLoaded(), false);
  }

  _DOMContentLoaded() {
    const firstHeading = document.querySelector('.c-entry__content > h2');
    const widgetAreas  = document.getElementsByClassName('l-heading-widget-area');

    this._forEachHtmlNodes(widgetAreas, (element) => {
      firstHeading.parentNode.insertBefore(element, firstHeading);
      element.setAttribute('aria-hidden', 'false');
    });
  }

  _forEachHtmlNodes(htmlNodes, callback) {
    if (0 < htmlNodes.length) {
      [].forEach.call(htmlNodes, (htmlNode) => callback(htmlNode));
    }
  }
}

new SnowMonkeyHeadingWidgetArea();
