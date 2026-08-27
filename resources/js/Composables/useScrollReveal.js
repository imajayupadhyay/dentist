import { onBeforeUnmount, onMounted } from 'vue';

/**
 * Reveals every `[data-rv]` element inside the given root once it scrolls into
 * view, by adding the `.in` class the design system animates against.
 *
 * Mirrors the IntersectionObserver in the original design document — same
 * threshold, same rootMargin, same one-shot behaviour (each element is
 * unobserved after it reveals).
 *
 * @param {import('vue').Ref<HTMLElement|null>} root
 */
export function useScrollReveal(root) {
    let observer = null;

    onMounted(() => {
        const el = root?.value;

        if (!el || typeof IntersectionObserver === 'undefined') {
            return;
        }

        observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('in');
                        observer.unobserve(entry.target);
                    }
                });
            },
            { threshold: 0.12, rootMargin: '0px 0px -6% 0px' },
        );

        el.querySelectorAll('[data-rv]').forEach((target) => observer.observe(target));
    });

    onBeforeUnmount(() => {
        observer?.disconnect();
        observer = null;
    });
}
