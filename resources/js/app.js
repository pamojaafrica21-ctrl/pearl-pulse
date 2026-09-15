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
    if (prefersReducedMotion()) return;

    document.querySelectorAll('[data-marquee]').forEach((track) => {
        const inner = track.querySelector('[data-marquee-inner]');
        if (!inner || inner.dataset.cloned === '1') return;

        inner.innerHTML = inner.innerHTML + inner.innerHTML;
        inner.dataset.cloned = '1';
        track.classList.add('marquee-active');
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

function boot() {
    document.documentElement.classList.add('js-motion');
    initReveals();
    initParallax();
    initMarquees();
    initHeaderShrink();
}

document.addEventListener('DOMContentLoaded', boot);
document.addEventListener('livewire:navigated', boot);
