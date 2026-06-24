/**
 * Horizontal factory / project card sliders on landing page.
 */
(function () {
	'use strict';

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

		if (!grid || !prev || !next) {
			return;
		}

		var maxScroll = grid.scrollWidth - grid.clientWidth;
		var atStart = grid.scrollLeft <= 2;
		var atEnd = grid.scrollLeft >= maxScroll - 2;
		var scrollable = maxScroll > 2;

		prev.disabled = !scrollable || atStart;
		next.disabled = !scrollable || atEnd;
		slider.classList.toggle('is-scrollable', scrollable);
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

		if (!grid || !prev || !next) {
			return;
		}

		prev.addEventListener('click', function () {
			scrollGrid(grid, -1);
		});

		next.addEventListener('click', function () {
			scrollGrid(grid, 1);
		});

		grid.addEventListener('scroll', function () {
			updateNav(slider);
		}, { passive: true });

		window.addEventListener('resize', function () {
			updateNav(slider);
		});

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
