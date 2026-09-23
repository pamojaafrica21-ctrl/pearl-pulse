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

function initCardScrollers() {
    document.querySelectorAll('[data-card-scroller]').forEach((root) => {
        if (root.dataset.scrollerBound === '1') return;
        root.dataset.scrollerBound = '1';

        const wrap = root.closest('[data-card-scroller-wrap]') || root.parentElement;
        const items = Array.from(root.querySelectorAll('[data-card-scroller-item]'));
        const prevBtn = wrap?.querySelector('[data-card-scroller-prev]');
        const nextBtn = wrap?.querySelector('[data-card-scroller-next]');

        if (items.length < 2) {
            prevBtn?.setAttribute('hidden', '');
            nextBtn?.setAttribute('hidden', '');
            return;
        }

        const pauseMs = Number(root.dataset.pause || 3200);
        const manualOnly = root.hasAttribute('data-manual');
        let index = 0;
        let timer = null;
        let held = false;
        let interacting = false;
        let resumeAfter = null;
        let drag = null;
        let suppressClick = false;

        const itemScrollLeft = (item) => {
            const rootRect = root.getBoundingClientRect();
            const itemRect = item.getBoundingClientRect();
            return root.scrollLeft + (itemRect.left - rootRect.left);
        };

        const nearestIndex = () => {
            const left = root.scrollLeft;
            let best = 0;
            let bestDist = Infinity;
            items.forEach((item, i) => {
                const dist = Math.abs(itemScrollLeft(item) - left);
                if (dist < bestDist) {
                    bestDist = dist;
                    best = i;
                }
            });
            return best;
        };

        const goTo = (i, behavior = 'smooth') => {
            index = ((i % items.length) + items.length) % items.length;
            root.scrollTo({ left: itemScrollLeft(items[index]), behavior });
        };

        const clearTimer = () => {
            if (timer) {
                clearTimeout(timer);
                timer = null;
            }
        };

        const schedule = () => {
            clearTimer();
            if (manualOnly || prefersReducedMotion() || held || interacting) return;
            timer = window.setTimeout(() => {
                goTo(index + 1);
                schedule();
            }, pauseMs);
        };

        const hold = () => {
            held = true;
            clearTimer();
        };

        const release = () => {
            held = false;
            index = nearestIndex();
            schedule();
        };

        const bumpInteraction = () => {
            interacting = true;
            clearTimer();
            if (resumeAfter) clearTimeout(resumeAfter);
            resumeAfter = window.setTimeout(() => {
                interacting = false;
                index = nearestIndex();
                schedule();
            }, 2200);
        };

        const step = (delta) => {
            index = nearestIndex();
            goTo(index + delta);
            bumpInteraction();
        };

        prevBtn?.addEventListener('click', () => step(-1));
        nextBtn?.addEventListener('click', () => step(1));

        root.addEventListener('mouseenter', hold);
        root.addEventListener('mouseleave', release);
        root.addEventListener('focusin', hold);
        root.addEventListener('focusout', (event) => {
            if (!root.contains(event.relatedTarget)) release();
        });
        root.addEventListener(
            'wheel',
            (event) => {
                if (Math.abs(event.deltaY) > Math.abs(event.deltaX)) {
                    root.scrollLeft += event.deltaY;
                    event.preventDefault();
                }
                bumpInteraction();
            },
            { passive: false }
        );
        root.addEventListener(
            'scroll',
            () => {
                if (!held && !drag) index = nearestIndex();
            },
            { passive: true }
        );

        root.addEventListener('pointerdown', (event) => {
            if (event.pointerType === 'mouse' && event.button !== 0) return;
            if (event.target.closest('a, button')) return;
            drag = {
                pointerId: event.pointerId,
                startX: event.clientX,
                startScroll: root.scrollLeft,
                moved: false,
            };
            root.classList.add('is-dragging');
            hold();
            try {
                root.setPointerCapture(event.pointerId);
            } catch {
                // Ignore capture failures on some browsers.
            }
        });

        root.addEventListener('pointermove', (event) => {
            if (!drag || drag.pointerId !== event.pointerId) return;
            const dx = event.clientX - drag.startX;
            if (Math.abs(dx) > 4) drag.moved = true;
            root.scrollLeft = drag.startScroll - dx;
        });

        const endDrag = (event) => {
            if (!drag || drag.pointerId !== event.pointerId) return;
            const wasDrag = drag.moved;
            drag = null;
            root.classList.remove('is-dragging');
            index = nearestIndex();
            if (wasDrag) {
                suppressClick = true;
                goTo(index, 'smooth');
                bumpInteraction();
            } else {
                release();
            }
        };

        root.addEventListener('pointerup', endDrag);
        root.addEventListener('pointercancel', endDrag);

        root.addEventListener(
            'click',
            (event) => {
                if (!suppressClick) return;
                event.preventDefault();
                event.stopPropagation();
                suppressClick = false;
            },
            true
        );

        const onVisibility = () => {
            if (document.hidden) {
                clearTimer();
            } else if (!held && !interacting) {
                schedule();
            }
        };
        document.addEventListener('visibilitychange', onVisibility);

        schedule();
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
    initCardScrollers();
    initHeaderShrink();
    initYouTubeHeroes();
}

document.addEventListener('DOMContentLoaded', boot);
document.addEventListener('livewire:navigated', boot);
