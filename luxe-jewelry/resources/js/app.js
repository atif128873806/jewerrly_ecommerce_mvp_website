import Alpine from 'alpinejs';
import collapse from '@alpinejs/collapse';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';
import Lenis from 'lenis';

Alpine.plugin(collapse);
gsap.registerPlugin(ScrollTrigger);

const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/* ---------------- Theme toggle ---------------- */
window.__toggleTheme = function () {
    const root = document.documentElement;
    root.classList.toggle('light');
    try {
        localStorage.setItem('theme', root.classList.contains('light') ? 'light' : 'dark');
    } catch (e) {}
};

/* ---------------- Alpine: carousel ---------------- */
Alpine.data('carousel', () => ({
    scrollNext() {
        const t = this.$refs.track;
        t.scrollBy({ left: t.clientWidth * 0.8, behavior: 'smooth' });
    },
    scrollPrev() {
        const t = this.$refs.track;
        t.scrollBy({ left: -t.clientWidth * 0.8, behavior: 'smooth' });
    },
}));

window.Alpine = Alpine;
Alpine.start();

/* ---------------- Smooth scroll (Lenis) ---------------- */
if (!reduceMotion) {
    const lenis = new Lenis({
        duration: 1.1,
        easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
        smoothWheel: true,
    });

    function raf(time) {
        lenis.raf(time);
        requestAnimationFrame(raf);
    }
    requestAnimationFrame(raf);

    lenis.on('scroll', ScrollTrigger.update);

    // Anchor links go through Lenis
    document.querySelectorAll('a[href^="#"]').forEach((a) => {
        a.addEventListener('click', (e) => {
            const id = a.getAttribute('href');
            if (id.length > 1) {
                const el = document.querySelector(id);
                if (el) {
                    e.preventDefault();
                    lenis.scrollTo(el, { offset: -80 });
                }
            }
        });
    });
}

/* ---------------- Animations ---------------- */
window.addEventListener('DOMContentLoaded', () => {
    if (reduceMotion) {
        document.querySelectorAll('.reveal').forEach((el) => (el.style.opacity = 1));
        return;
    }

    // Hero intro
    const heroEls = gsap.utils
        .toArray('[data-hero]')
        .sort((a, b) => a.dataset.hero - b.dataset.hero);
    gsap.set(heroEls, { opacity: 0, y: 30 });
    gsap.to(heroEls, {
        opacity: 1,
        y: 0,
        duration: 1.1,
        ease: 'power3.out',
        stagger: 0.13,
        delay: 0.15,
    });

    // Subtle hero settle — very gentle, never a strong zoom
    const heroImg = document.querySelector('[data-hero-img]');
    if (heroImg) {
        gsap.fromTo(heroImg, { scale: 1.04 }, { scale: 1, duration: 1.8, ease: 'power2.out' });
    }

    // Scroll reveals
    gsap.utils.toArray('[data-reveal]').forEach((el) => {
        gsap.fromTo(
            el,
            { opacity: 0, y: 40 },
            {
                opacity: 1,
                y: 0,
                duration: 0.9,
                ease: 'power3.out',
                scrollTrigger: { trigger: el, start: 'top 88%', once: true },
            }
        );
    });

    // Parallax images
    gsap.utils.toArray('[data-parallax]').forEach((img) => {
        gsap.fromTo(
            img,
            { yPercent: -9, scale: 1.18 },
            {
                yPercent: 9,
                scale: 1.18,
                ease: 'none',
                scrollTrigger: {
                    trigger: img,
                    start: 'top bottom',
                    end: 'bottom top',
                    scrub: true,
                },
            }
        );
    });
});
