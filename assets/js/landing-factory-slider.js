/**
 * Horizontal factory / project card sliders on landing page.
 */
(function () {
	'use strict';

	var MOBILE_MQ = window.matchMedia('(max-width: 849px)');

	function isMobilePeek() {
		return MOBILE_MQ.matches;
	}

	function getStep(grid) {
		var card = grid.querySelector('.vs-factory-card');
		if (!card) {
			return grid.clientWidth;
		}

		var styles = window.getComputedStyle(grid);
		var gap = parseFloat(styles.columnGap || styles.gap || '0') || 0;

		return card.offsetWidth + gap;
	}

	function updateNav(slider) {
		var grid = slider.querySelector('.vs-factory-grid');
		var prev = slider.querySelector('.vs-factory-slider__nav--prev');
		var next = slider.querySelector('.vs-factory-slider__nav--next');

		if (!grid) {
			return;
		}

		var maxScroll = grid.scrollWidth - grid.clientWidth;
		var scrollable = maxScroll > 2;

		slider.classList.toggle('is-scrollable', scrollable);
		slider.classList.toggle('is-mobile-peek', isMobilePeek());

		if (!prev || !next || isMobilePeek()) {
			return;
		}

		var atStart = grid.scrollLeft <= 2;
		var atEnd = grid.scrollLeft >= maxScroll - 2;

		prev.disabled = !scrollable || atStart;
		next.disabled = !scrollable || atEnd;
	}

	function scrollGrid(grid, direction) {
		grid.scrollBy({
			left: direction * getStep(grid),
			behavior: 'smooth',
		});
	}

	function initSlider(slider) {
		var grid = slider.querySelector('.vs-factory-grid');
		var prev = slider.querySelector('.vs-factory-slider__nav--prev');
		var next = slider.querySelector('.vs-factory-slider__nav--next');

		if (!grid) {
			return;
		}

		if (prev) {
			prev.addEventListener('click', function () {
				scrollGrid(grid, -1);
			});
		}

		if (next) {
			next.addEventListener('click', function () {
				scrollGrid(grid, 1);
			});
		}

		grid.addEventListener(
			'scroll',
			function () {
				updateNav(slider);
			},
			{ passive: true }
		);

		window.addEventListener('resize', function () {
			updateNav(slider);
		});

		if (typeof MOBILE_MQ.addEventListener === 'function') {
			MOBILE_MQ.addEventListener('change', function () {
				updateNav(slider);
			});
		} else if (typeof MOBILE_MQ.addListener === 'function') {
			MOBILE_MQ.addListener(function () {
				updateNav(slider);
			});
		}

		updateNav(slider);
	}

	function boot() {
		document.querySelectorAll('.vs-factory-slider').forEach(initSlider);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
})();
