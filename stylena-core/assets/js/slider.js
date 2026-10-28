/**
 * Stylena Core - image slider (continuous marquee-style loop).
 * Ported from the Stylena theme (js/custom.js). Only loaded when the active
 * theme does not already provide the slider script.
 */
( function () {
	'use strict';

	var reducedMotion = window.matchMedia ? window.matchMedia( '(prefers-reduced-motion: reduce)' ) : { matches: false };

	function StylenaImageSlider( element ) {
		this.slider = element;
		this.wrapper = element.querySelector( '.slider-wrapper' );
		if ( ! this.wrapper ) {
			return;
		}
		this.originalSlides = Array.prototype.slice.call( this.wrapper.querySelectorAll( '.slider-slide' ) );
		this.realTotal = this.originalSlides.length;
		if ( 0 === this.realTotal ) {
			return;
		}
		this.autoplay = 'false' !== element.dataset.autoplay && ! reducedMotion.matches;
		this.autoplaySpeed = parseInt( element.dataset.autoplaySpeed, 10 ) || 4000;
		this.slidesToShow = this.getSlidesToShow();
		this.slideWidth = 0;
		this.translate = 0;
		this.startTranslate = 0;
		this.speedPxPerMs = 0;
		this.rafId = null;
		this.lastTime = null;
		this.nextAppendIndex = 0;
		this.init();
	}

	StylenaImageSlider.prototype.getSlidesToShow = function () {
		var width = window.innerWidth;
		if ( width <= 480 ) {
			return 1;
		}
		return width <= 768 ? 2 : 3;
	};

	StylenaImageSlider.prototype.init = function () {
		this.build();
		this.measure();
		this.translate = this.startTranslate;
		this.wrapper.style.transform = 'translateX(' + this.translate + 'px)';
		if ( this.autoplay ) {
			this.start();
			this.addPauseControl();
		}
	};

	StylenaImageSlider.prototype.build = function () {
		var k = this.slidesToShow;
		var self = this;
		while ( this.wrapper.firstChild ) {
			this.wrapper.removeChild( this.wrapper.firstChild );
		}
		this.originalSlides.forEach( function ( node, index ) {
			var copy = node.cloneNode( true );
			copy.setAttribute( 'data-slide-index', index );
			self.wrapper.appendChild( copy );
		} );
		if ( this.realTotal > k ) {
			for ( var i = 0; i < k; i++ ) {
				var idx = i % this.realTotal;
				var clone = this.originalSlides[ idx ].cloneNode( true );
				clone.setAttribute( 'data-slide-index', idx );
				clone.setAttribute( 'aria-hidden', 'true' );
				clone.classList.add( 'clone' );
				this.wrapper.appendChild( clone );
			}
			this.nextAppendIndex = k % this.realTotal;
		}
	};

	StylenaImageSlider.prototype.measure = function () {
		var first = this.wrapper.querySelector( '.slider-slide' );
		if ( ! first ) {
			return;
		}
		this.slideWidth = first.getBoundingClientRect().width;
		var k = this.getSlidesToShow();
		var cs = window.getComputedStyle( this.slider );
		var padL = parseFloat( cs.paddingLeft ) || 0;
		var padR = parseFloat( cs.paddingRight ) || 0;
		var width = this.slider.getBoundingClientRect().width || window.innerWidth;
		var inner = Math.max( 0, width - padL - padR );
		var gap = parseFloat( window.getComputedStyle( this.wrapper ).gap ) || 0;
		var visible = k * this.slideWidth + Math.max( 0, ( k - 1 ) * gap );
		var shift = window.innerWidth <= 480 ? inner * 0.05 : 0;
		this.startTranslate = padL + ( inner - visible ) / 2 - shift;
		this.speedPxPerMs = this.slideWidth / this.autoplaySpeed;
	};

	StylenaImageSlider.prototype.recycle = function () {
		if ( ! this.slideWidth ) {
			return;
		}
		while ( this.translate <= this.startTranslate - this.slideWidth - 0.5 ) {
			var first = this.wrapper.firstElementChild;
			if ( ! first ) {
				break;
			}
			this.wrapper.removeChild( first );
			var idx = this.nextAppendIndex % this.realTotal;
			var node = this.originalSlides[ idx ].cloneNode( true );
			node.setAttribute( 'data-slide-index', idx );
			node.classList.add( 'recycled' );
			this.wrapper.appendChild( node );
			this.nextAppendIndex = ( this.nextAppendIndex + 1 ) % this.realTotal;
			this.translate += this.slideWidth;
		}
	};

	StylenaImageSlider.prototype.start = function () {
		var self = this;
		if ( this.rafId ) {
			return;
		}
		this.lastTime = performance.now();
		var tick = function ( now ) {
			self.translate -= self.speedPxPerMs * ( now - self.lastTime );
			self.lastTime = now;
			self.recycle();
			self.wrapper.style.transform = 'translateX(' + self.translate + 'px)';
			self.rafId = requestAnimationFrame( tick );
		};
		this.rafId = requestAnimationFrame( tick );
	};

	StylenaImageSlider.prototype.stop = function () {
		if ( this.rafId ) {
			cancelAnimationFrame( this.rafId );
			this.rafId = null;
		}
	};

	// WCAG 2.2.2: moving content lasting more than 5s needs a pause control.
	StylenaImageSlider.prototype.addPauseControl = function () {
		var self = this;
		var pauseLabel = this.slider.dataset.pauseLabel || 'Pause slider';
		var playLabel = this.slider.dataset.playLabel || 'Play slider';
		var btn = document.createElement( 'button' );
		btn.type = 'button';
		btn.className = 'slider-pause-toggle';
		btn.setAttribute( 'aria-pressed', 'false' );
		btn.setAttribute( 'aria-label', pauseLabel );
		btn.textContent = '❚❚';
		btn.addEventListener( 'click', function () {
			var paused = 'true' === btn.getAttribute( 'aria-pressed' );
			if ( paused ) {
				self.start();
			} else {
				self.stop();
			}
			btn.setAttribute( 'aria-pressed', paused ? 'false' : 'true' );
			btn.setAttribute( 'aria-label', paused ? pauseLabel : playLabel );
			btn.textContent = paused ? '❚❚' : '▶';
		} );
		this.slider.appendChild( btn );
	};

	document.addEventListener( 'DOMContentLoaded', function () {
		var sliders = document.querySelectorAll( '.stylena-image-slider' );
		Array.prototype.forEach.call( sliders, function ( el ) {
			new StylenaImageSlider( el );
		} );
	} );

	window.StylenaCoreImageSlider = StylenaImageSlider;
}() );
