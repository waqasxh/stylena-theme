/**
 * Portfolio lightbox and category filter (vanilla JS, no dependencies).
 *
 * Lightbox: opens from buttons.stylena-lb-trigger, role="dialog" aria-modal, labelled by the
 * item title, Esc closes, arrows/Home/End navigate, Tab is trapped inside, focus returns to the
 * trigger on close. Text is set via textContent only. Transitions are disabled in CSS under
 * prefers-reduced-motion.
 *
 * Filter: buttons[data-filter] inside .stylena-portfolio-filters toggle cards by data-cats.
 */
(function () {
	'use strict';

	var i18n = window.stylenaPortfolioLb || {};
	var overlay = null;
	var imgEl, titleEl, capEl, closeBtn, prevBtn, nextBtn;
	var current = [];
	var index = 0;
	var lastTrigger = null;

	function el(tag, cls, attrs) {
		var node = document.createElement(tag);
		if (cls) { node.className = cls; }
		if (attrs) {
			Object.keys(attrs).forEach(function (k) { node.setAttribute(k, attrs[k]); });
		}
		return node;
	}

	function build() {
		overlay = el('div', 'stylena-lb', {
			role: 'dialog',
			'aria-modal': 'true',
			'aria-labelledby': 'stylena-lb-title',
			'aria-label': i18n.label || 'Image viewer',
			hidden: ''
		});
		// aria-labelledby wins over aria-label when the title element has text.
		var inner = el('div', 'stylena-lb__inner');
		closeBtn = el('button', 'stylena-lb__btn stylena-lb__close', { type: 'button', 'aria-label': i18n.close || 'Close' });
		closeBtn.textContent = '×';
		prevBtn = el('button', 'stylena-lb__btn stylena-lb__prev', { type: 'button', 'aria-label': i18n.prev || 'Previous image' });
		prevBtn.textContent = '‹';
		nextBtn = el('button', 'stylena-lb__btn stylena-lb__next', { type: 'button', 'aria-label': i18n.next || 'Next image' });
		nextBtn.textContent = '›';
		var fig = el('figure', 'stylena-lb__figure');
		imgEl = el('img', 'stylena-lb__img', { alt: '' });
		var cap = el('figcaption', 'stylena-lb__caption');
		titleEl = el('strong', 'stylena-lb__title', { id: 'stylena-lb-title' });
		capEl = el('span', 'stylena-lb__text');
		cap.appendChild(titleEl);
		cap.appendChild(capEl);
		fig.appendChild(imgEl);
		fig.appendChild(cap);
		inner.appendChild(closeBtn);
		inner.appendChild(prevBtn);
		inner.appendChild(fig);
		inner.appendChild(nextBtn);
		overlay.appendChild(inner);
		document.body.appendChild(overlay);

		closeBtn.addEventListener('click', close);
		prevBtn.addEventListener('click', function () { go(-1); });
		nextBtn.addEventListener('click', function () { go(1); });
		overlay.addEventListener('click', function (e) {
			if (e.target === overlay || e.target === inner) { close(); }
		});
		overlay.addEventListener('keydown', onKey);

		var startX = null;
		overlay.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; }, { passive: true });
		overlay.addEventListener('touchend', function (e) {
			if (startX === null) { return; }
			var dx = e.changedTouches[0].clientX - startX;
			startX = null;
			if (Math.abs(dx) > 50) { go((dx < 0) !== isRtl() ? 1 : -1); }
		}, { passive: true });
	}

	function isRtl() {
		return document.documentElement.dir === 'rtl' || document.body.classList.contains('rtl') || document.body.classList.contains('is-rtl');
	}

	function visibleTriggers() {
		var all = document.querySelectorAll('.stylena-lb-trigger');
		return Array.prototype.filter.call(all, function (t) {
			var col = t.closest('.stylena-portfolio-col');
			return !col || !col.hidden;
		});
	}

	function show(i) {
		var t = current[i];
		index = i;
		imgEl.src = t.getAttribute('data-lb-src');
		imgEl.alt = t.getAttribute('data-lb-title') || '';
		titleEl.textContent = t.getAttribute('data-lb-title') || '';
		capEl.textContent = t.getAttribute('data-lb-caption') || '';
		var multi = current.length > 1;
		prevBtn.hidden = !multi;
		nextBtn.hidden = !multi;
	}

	function go(step) {
		if (current.length < 2) { return; }
		show((index + step + current.length) % current.length);
	}

	function focusables() {
		return Array.prototype.filter.call(
			overlay.querySelectorAll('button'),
			function (b) { return !b.hidden; }
		);
	}

	function onKey(e) {
		if (e.key === 'Escape') {
			e.preventDefault();
			close();
		} else if (e.key === 'ArrowLeft') {
			go(isRtl() ? 1 : -1);
		} else if (e.key === 'ArrowRight') {
			go(isRtl() ? -1 : 1);
		} else if (e.key === 'Tab') {
			var f = focusables();
			if (!f.length) { return; }
			var first = f[0];
			var last = f[f.length - 1];
			if (e.shiftKey && document.activeElement === first) {
				e.preventDefault();
				last.focus();
			} else if (!e.shiftKey && document.activeElement === last) {
				e.preventDefault();
				first.focus();
			} else if (f.indexOf(document.activeElement) === -1) {
				e.preventDefault();
				first.focus();
			}
		}
	}

	function open(trigger) {
		if (overlay && !overlay.hidden) { return; }
		if (!overlay) { build(); }
		lastTrigger = trigger;
		current = visibleTriggers();
		var i = current.indexOf(trigger);
		show(i < 0 ? 0 : i);
		overlay.hidden = false;
		document.documentElement.classList.add('stylena-lb-open');
		// Hide the rest of the page from assistive tech while open.
		Array.prototype.forEach.call(document.body.children, function (n) {
			if (n !== overlay && n.nodeType === 1) {
				n.setAttribute('data-lb-inert', n.hasAttribute('inert') ? '1' : '0');
				n.setAttribute('inert', '');
			}
		});
		closeBtn.focus();
	}

	function close() {
		if (!overlay || overlay.hidden) { return; }
		overlay.hidden = true;
		imgEl.removeAttribute('src');
		document.documentElement.classList.remove('stylena-lb-open');
		Array.prototype.forEach.call(document.body.children, function (n) {
			if (n.getAttribute && n.hasAttribute('data-lb-inert')) {
				if (n.getAttribute('data-lb-inert') === '0') { n.removeAttribute('inert'); }
				n.removeAttribute('data-lb-inert');
			}
		});
		if (lastTrigger && document.contains(lastTrigger)) { lastTrigger.focus(); }
		lastTrigger = null;
	}

	document.addEventListener('click', function (e) {
		var t = e.target.closest ? e.target.closest('.stylena-lb-trigger') : null;
		if (t) {
			e.preventDefault();
			open(t);
		}
	});

	// Category filters.
	document.addEventListener('click', function (e) {
		var btn = e.target.closest ? e.target.closest('.stylena-portfolio-filters [data-filter]') : null;
		if (!btn) { return; }
		var group = btn.closest('.stylena-portfolio-filters');
		var scope = group.parentNode;
		var filter = btn.getAttribute('data-filter');

		Array.prototype.forEach.call(group.querySelectorAll('[data-filter]'), function (b) {
			b.setAttribute('aria-pressed', b === btn ? 'true' : 'false');
		});

		Array.prototype.forEach.call(scope.querySelectorAll('.stylena-portfolio-col'), function (col) {
			var cats = (col.getAttribute('data-cats') || '').split(' ');
			var matches = filter === '*' || cats.indexOf(filter) !== -1;
			var pendingExtra = col.classList.contains('stylena-portfolio-extra');
			col.hidden = !matches || (filter === '*' && pendingExtra);
		});

		var more = scope.querySelector('#load-more-portfolio');
		if (more) {
			var left = scope.querySelectorAll('.stylena-portfolio-extra').length;
			more.parentNode.hidden = filter !== '*' || left === 0;
		}
	});
})();
