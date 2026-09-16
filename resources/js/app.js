/**
 * Pearl Pulse — motion & interaction layer
 * Scroll reveals, staggered children, subtle parallax, card lift.
 */

const prefersReducedMotion = () =>
    window.matchMedia('(prefers-reduced-motion: reduce)').matches;

function initReveals() {
    const nodes = document.querySelectorAll('.reveal, .reveal-stagger');
    if (!nodes.length) return;

    if (prefersReducedMotion()) {
        nodes.forEach((el) => el.classList.add('is-visible'));
        return;
    }

    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            });
        },
        { rootMargin: '0px 0px -8% 0px', threshold: 0.12 }
    );

    nodes.forEach((el) => observer.observe(el));
}

function initParallax() {
    if (prefersReducedMotion()) return;

    const layers = Array.from(document.querySelectorAll('[data-parallax]'));
    if (!layers.length) return;

    let ticking = false;

    const update = () => {
        const vh = window.innerHeight;
        layers.forEach((el) => {
            const speed = Number(el.getAttribute('data-parallax') || 0.12);
            const rect = el.getBoundingClientRect();
            const progress = (vh / 2 - (rect.top + rect.height / 2)) / vh;
            const y = progress * speed * 80;
            el.style.transform = `translate3d(0, ${y.toFixed(2)}px, 0)`;
        });
        ticking = false;
    };

    const onScroll = () => {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(update);
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    update();
}

function initMarquees() {
    document.querySelectorAll('[data-marquee]').forEach((track) => {
        // Markup already duplicates groups for a seamless CSS loop.
        // Only wire pause-on-focus for keyboard users.
        track.addEventListener('focusin', () => track.classList.add('is-paused'));
        track.addEventListener('focusout', () => track.classList.remove('is-paused'));
    });
}

function initHeaderShrink() {
    const header = document.querySelector('[data-site-header]');
    if (!header) return;

    const onScroll = () => {
        header.classList.toggle('is-scrolled', window.scrollY > 24);
    };

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
}

function initYouTubeHeroes() {
    const frames = Array.from(document.querySelectorAll('.hero-youtube__frame'));
    if (!frames.length) return;

    const isYouTubeOrigin = (origin) => {
        try {
            const host = new URL(origin).hostname.replace(/^www\./, '');
            return host === 'youtube.com' || host === 'youtube-nocookie.com';
        } catch {
            return false;
        }
    };

    const markPlaying = (source) => {
        frames.forEach((frame) => {
            if (frame.contentWindow === source) {
                frame.classList.add('is-playing');
            }
        });
    };

    window.addEventListener('message', (event) => {
        if (!isYouTubeOrigin(event.origin)) return;

        let data = event.data;
        if (typeof data === 'string') {
            try {
                data = JSON.parse(data);
            } catch {
                return;
            }
        }
        if (!data || typeof data !== 'object') return;

        const state =
            data.event === 'onStateChange'
                ? data.info
                : data.event === 'infoDelivery'
                  ? data.info?.playerState
                  : null;

        // 1 = playing
        if (state === 1) {
            markPlaying(event.source);
        }
    });

    const listen = (frame) => {
        try {
            frame.contentWindow?.postMessage(
                JSON.stringify({ event: 'listening', id: frame.id || 1 }),
                '*'
            );
            frame.contentWindow?.postMessage(
                JSON.stringify({
                    event: 'command',
                    func: 'addEventListener',
                    args: ['onStateChange'],
                }),
                '*'
            );
        } catch {
            // Cross-origin / blocked embeds stay on the poster image.
        }
    };

    frames.forEach((frame) => {
        if (frame.dataset.ytBound === '1') return;
        frame.dataset.ytBound = '1';
        frame.addEventListener('load', () => listen(frame));
        // In case load already fired
        if (frame.contentWindow) {
            listen(frame);
        }
    });
}

function boot() {
    document.documentElement.classList.add('js-motion');
    initReveals();
    initParallax();
    initMarquees();
    initHeaderShrink();
    initYouTubeHeroes();
}

document.addEventListener('DOMContentLoaded', boot);
document.addEventListener('livewire:navigated', boot);
