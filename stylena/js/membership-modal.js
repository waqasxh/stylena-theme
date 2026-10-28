/**
 * Membership page modal (vanilla JS, no globals).
 *
 * Open: any [data-membership-open] element or #membership-join-btn.
 * Close: .membership-modal-close, overlay click, Escape key.
 */
(function () {
	'use strict';

	var overlay = document.getElementById('membership-modal-overlay');
	if (!overlay) {
		return;
	}

	var lastFocused = null;

	function open() {
		lastFocused = document.activeElement;
		overlay.style.display = 'flex';
		document.body.style.overflow = 'hidden';
		var closeBtn = overlay.querySelector('.membership-modal-close');
		if (closeBtn) {
			closeBtn.focus();
		}
	}

	function close() {
		overlay.style.display = 'none';
		document.body.style.overflow = '';
		if (lastFocused && typeof lastFocused.focus === 'function') {
			lastFocused.focus();
		}
	}

	if (!overlay.hasAttribute('role')) {
		overlay.setAttribute('role', 'dialog');
		overlay.setAttribute('aria-modal', 'true');
		var title = overlay.querySelector('.membership-modal-title');
		if (title) {
			if (!title.id) {
				title.id = 'membership-modal-title';
			}
			overlay.setAttribute('aria-labelledby', title.id);
		}
	}

	document.addEventListener('click', function (e) {
		var target = e.target;
		if (!target || !target.closest) {
			return;
		}
		if (target.closest('[data-membership-open], #membership-join-btn')) {
			e.preventDefault();
			open();
			return;
		}
		if (target.closest('.membership-modal-close') || target === overlay) {
			e.preventDefault();
			close();
		}
	});

	document.addEventListener('keydown', function (e) {
		if (overlay.style.display !== 'flex') {
			return;
		}
		if (e.key === 'Escape' || e.key === 'Esc') {
			close();
			return;
		}
		if (e.key === 'Tab') {
			// Keep keyboard focus inside the dialog.
			var focusable = overlay.querySelectorAll('a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])');
			if (!focusable.length) {
				return;
			}
			var first = focusable[0];
			var last = focusable[focusable.length - 1];
			if (e.shiftKey && document.activeElement === first) {
				e.preventDefault();
				last.focus();
			} else if (!e.shiftKey && document.activeElement === last) {
				e.preventDefault();
				first.focus();
			} else if (!overlay.contains(document.activeElement)) {
				e.preventDefault();
				first.focus();
			}
		}
	});
})();
