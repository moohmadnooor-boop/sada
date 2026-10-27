/**
 * Sada (صدى) — front-end interactions.
 * Everything here progressively enhances server-rendered Blade pages:
 * each form still works without JavaScript.
 */

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

/* ------------------------------------------------------------------ */
/* Helpers                                                              */
/* ------------------------------------------------------------------ */

async function postJson(url, method = 'POST') {
    const response = await fetch(url, {
        method,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    });

    if (response.status === 401 || response.status === 419) {
        window.location.href = '/login';
        throw new Error('unauthenticated');
    }

    if (!response.ok) {
        throw new Error(`Request failed: ${response.status}`);
    }

    return response.json();
}

function toast(message) {
    const stack = document.getElementById('toasts');
    if (!stack) {
        return;
    }
    const el = document.createElement('div');
    el.className = 'toast';
    el.textContent = message;
    stack.appendChild(el);
    dismissLater(el);
}

function dismissLater(el, delay = 3200) {
    setTimeout(() => {
        el.classList.add('is-leaving');
        el.addEventListener('animationend', () => el.remove(), { once: true });
    }, delay);
}

function burst(button) {
    if (prefersReducedMotion) {
        return;
    }
    for (let i = 0; i < 2; i++) {
        const ring = document.createElement('span');
        ring.className = 'burst';
        ring.style.animationDelay = `${i * 120}ms`;
        button.appendChild(ring);
        ring.addEventListener('animationend', () => ring.remove(), { once: true });
    }
    button.classList.remove('is-popping');
    void button.offsetWidth;
    button.classList.add('is-popping');
}

function quoteDataFrom(el) {
    const holder = el.closest('[data-quote]');
    return {
        content: holder?.dataset.content ?? '',
        author: holder?.dataset.author ?? '',
        sharer: holder?.dataset.sharer ?? '',
        url: holder?.dataset.url ?? window.location.href,
    };
}

function shareText({ content, author }) {
    return `«${content}»${author ? ` — ${author}` : ''}`;
}

async function copyToClipboard(text) {
    try {
        await navigator.clipboard.writeText(text);
    } catch {
        const area = document.createElement('textarea');
        area.value = text;
        document.body.appendChild(area);
        area.select();
        document.execCommand('copy');
        area.remove();
    }
}

/* ------------------------------------------------------------------ */
/* Theme (night / day)                                                  */
/* ------------------------------------------------------------------ */

function initTheme() {
    document.querySelectorAll('[data-theme-toggle]').forEach((button) => {
        button.addEventListener('click', () => {
            const next = document.documentElement.dataset.theme === 'day' ? 'night' : 'day';
            document.documentElement.dataset.theme = next;
            try {
                localStorage.setItem('sada-theme', next);
            } catch {
                /* storage unavailable */
            }
        });
    });
}

/* ------------------------------------------------------------------ */
/* Navigation: overlay nav, dropdowns, mobile menu                      */
/* ------------------------------------------------------------------ */

function initNavigation() {
    const nav = document.querySelector('[data-nav].nav--overlay');
    if (nav) {
        const onScroll = () => nav.classList.toggle('is-scrolled', window.scrollY > 40);
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
    }

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-dropdown-trigger]');
        document.querySelectorAll('[data-dropdown-menu].is-open').forEach((menu) => {
            if (!trigger || !menu.parentElement.contains(trigger)) {
                menu.classList.remove('is-open');
                menu.parentElement.querySelector('[data-dropdown-trigger]')?.setAttribute('aria-expanded', 'false');
            }
        });
        if (trigger) {
            const menu = trigger.parentElement.querySelector('[data-dropdown-menu]');
            const open = menu.classList.toggle('is-open');
            trigger.setAttribute('aria-expanded', String(open));
        }
    });

    document.querySelector('[data-nav] .is-active')?.scrollIntoView({ block: 'nearest', inline: 'center' });

    if (window.location.hash === '#search') {
        document.getElementById('search')?.focus();
    }
}

/* ------------------------------------------------------------------ */
/* Modals                                                               */
/* ------------------------------------------------------------------ */

function openModal(modal) {
    modal.classList.add('is-open');
    document.body.style.overflow = 'hidden';
    setTimeout(() => modal.querySelector('textarea, input:not([type=hidden])')?.focus(), 150);
}

function closeModal(modal) {
    modal.classList.remove('is-open');
    document.body.style.overflow = '';
}

function initModals() {
    document.addEventListener('click', (event) => {
        const opener = event.target.closest('[data-open-modal]');
        if (opener) {
            const modal = document.getElementById(opener.dataset.openModal);
            if (opener.dataset.preselectBook) {
                const bookSelect = modal.querySelector('[name="book_id"]');
                const book = bookSelect?.querySelector(`option[value="${opener.dataset.preselectBook}"]`);
                if (book) {
                    bookSelect.value = book.value;
                    const author = modal.querySelector('[name="author"]');
                    if (author && !author.value) {
                        author.value = book.textContent.split(' — ')[1]?.trim() ?? '';
                    }
                }
            }
            openModal(modal);
            return;
        }
        const closer = event.target.closest('[data-close-modal]');
        if (closer) {
            closeModal(closer.closest('.modal'));
            return;
        }
        if (event.target.classList.contains('modal')) {
            closeModal(event.target);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            document.querySelectorAll('.modal.is-open').forEach(closeModal);
        }
    });

    document.querySelectorAll('.modal[data-open-on-load]').forEach(openModal);
}

/* ------------------------------------------------------------------ */
/* Character counters                                                   */
/* ------------------------------------------------------------------ */

function initCounters() {
    document.querySelectorAll('[data-counter-max]').forEach((field) => {
        const counter = document.querySelector(`[data-counter-for="${field.id}"]`);
        if (!counter) {
            return;
        }
        const max = Number(field.dataset.counterMax);
        const min = Number(field.dataset.counterMin || 1);
        const update = () => {
            const length = [...field.value].length;
            counter.textContent = `${length} / ${max}`;
            counter.classList.toggle('is-over', length > max || (length > 0 && length < min));
            counter.classList.toggle('is-ok', length >= min && length <= max);
        };
        field.addEventListener('input', update);
        update();
    });
}

/* ------------------------------------------------------------------ */
/* Likes, bookmarks, follows                                            */
/* ------------------------------------------------------------------ */

function initEngagement() {
    document.addEventListener('click', async (event) => {
        const like = event.target.closest('[data-like]');
        const bookmark = event.target.closest('[data-bookmark]');
        const follow = event.target.closest('[data-follow]');
        const button = like || bookmark || follow;
        if (!button || button.dataset.busy) {
            return;
        }

        button.dataset.busy = '1';
        try {
            const result = await postJson(button.dataset.url);
            button.classList.toggle('is-active', result.active);
            button.setAttribute('aria-pressed', String(result.active));

            if (like) {
                button.querySelector('[data-count]').textContent = result.count;
                if (result.active) {
                    burst(button);
                }
            }
            if (bookmark) {
                if (result.active) {
                    burst(button);
                }
                toast(result.active ? 'تم الحفظ في محفوظاتك 🔖' : 'أُزيل من محفوظاتك');
            }
            if (follow) {
                button.classList.toggle('btn-primary', !result.active);
                button.classList.toggle('btn-ghost', result.active);
                button.querySelector('[data-follow-label]').textContent = result.active ? 'تتابعه' : 'متابعة';
                const counter = document.querySelector('[data-follower-count]');
                if (counter) {
                    counter.textContent = result.count;
                }
                toast(result.active ? 'ستصلك اقتباساته في صفحة متابعاتي ✨' : 'ألغيت المتابعة');
            }
        } catch (error) {
            if (error.message !== 'unauthenticated') {
                toast('حدث خطأ، حاول مرة أخرى');
            }
        } finally {
            delete button.dataset.busy;
        }
    });
}

/* ------------------------------------------------------------------ */
/* Copy & share                                                         */
/* ------------------------------------------------------------------ */

function initSharing() {
    document.addEventListener('click', async (event) => {
        const copy = event.target.closest('[data-copy]');
        const copyLink = event.target.closest('[data-copy-link]');
        if (copy) {
            const data = quoteDataFrom(copy);
            await copyToClipboard(`${shareText(data)}\n\nعبر صدى: ${data.url}`);
            toast('تم نسخ الاقتباس 📋');
        }
        if (copyLink) {
            await copyToClipboard(quoteDataFrom(copyLink).url);
            toast('تم نسخ الرابط 🔗');
        }
    });
}

/* ------------------------------------------------------------------ */
/* Inline edit + instant delete (owner only)                            */
/* ------------------------------------------------------------------ */

function initOwnerActions() {
    document.addEventListener('click', (event) => {
        const toggle = event.target.closest('[data-edit-toggle]');
        const cancel = event.target.closest('[data-edit-cancel]');
        const card = (toggle || cancel)?.closest('[data-quote]');
        if (!card) {
            return;
        }
        const form = card.querySelector('[data-quote-edit]');
        const view = card.querySelector('[data-quote-view]');
        const editing = toggle ? form.classList.contains('hidden') : false;

        if (cancel) {
            form.reset();
            initCounters();
        }
        form.classList.toggle('hidden', !editing);
        form.classList.toggle('flex', editing);
        view.classList.toggle('hidden', editing);
        if (editing) {
            form.querySelector('textarea')?.focus();
        }
    });

    document.addEventListener('submit', async (event) => {
        const form = event.target.closest('[data-delete-form]');
        if (!form) {
            return;
        }
        event.preventDefault();
        const card = form.closest('[data-quote]');
        try {
            await postJson(form.action, 'DELETE');
            card.style.maxHeight = `${card.offsetHeight}px`;
            card.classList.add('is-removing');
            setTimeout(() => {
                card.remove();
                if (window.location.pathname === `/quotes/${card.dataset.quoteId}`) {
                    window.location.href = '/quotes';
                }
            }, 400);
            toast('تم حذف الاقتباس');
        } catch {
            form.submit();
        }
    });
}

/* ------------------------------------------------------------------ */
/* Quote → image (canvas)                                               */
/* ------------------------------------------------------------------ */

const imageStyles = {
    night: { from: '#0e1424', to: '#1b3442', text: '#f4f1ea', accent: '#4fd1c5', muted: '#9aa3b5' },
    sand: { from: '#f3ebdd', to: '#e2c6a4', text: '#2a2420', accent: '#b5653a', muted: '#6b5a4c' },
    sea: { from: '#f2f6f8', to: '#c6e2ef', text: '#123355', accent: '#1f4e79', muted: '#4f6b85' },
};

let currentImageQuote = null;
let currentImageStyle = 'night';

function wrapLines(ctx, text, maxWidth) {
    const words = text.split(/\s+/);
    const lines = [];
    let line = '';
    words.forEach((word) => {
        const test = line ? `${line} ${word}` : word;
        if (ctx.measureText(test).width > maxWidth && line) {
            lines.push(line);
            line = word;
        } else {
            line = test;
        }
    });
    if (line) {
        lines.push(line);
    }
    return lines;
}

async function drawQuoteImage(canvas, quote, styleName) {
    const style = imageStyles[styleName];
    const ctx = canvas.getContext('2d');
    const { width: w, height: h } = canvas;

    // Pass the actual Arabic text so the browser fetches the Arabic unicode-range subset of each font.
    await Promise.all([
        document.fonts.load('700 64px Amiri', `${quote.content} ${quote.author} ”`),
        document.fonts.load('700 48px "Reem Kufi"', 'صدى'),
        document.fonts.load('500 32px "IBM Plex Sans Arabic"', `شاركه ${quote.sharer}`),
    ]).catch(() => {});

    const gradient = ctx.createLinearGradient(0, 0, w, h);
    gradient.addColorStop(0, style.from);
    gradient.addColorStop(1, style.to);
    ctx.fillStyle = gradient;
    ctx.fillRect(0, 0, w, h);

    ctx.strokeStyle = style.accent;
    for (let i = 1; i <= 7; i++) {
        ctx.globalAlpha = 0.22 - i * 0.025;
        ctx.lineWidth = 3;
        ctx.beginPath();
        ctx.arc(w * 0.85, h * 0.12, i * 85, 0, Math.PI * 2);
        ctx.stroke();
    }
    ctx.globalAlpha = 1;

    ctx.direction = 'rtl';
    ctx.textAlign = 'center';
    ctx.fillStyle = style.accent;
    ctx.font = '700 220px Amiri';
    ctx.globalAlpha = 0.5;
    ctx.fillText('”', w / 2, 420);
    ctx.globalAlpha = 1;

    let fontSize = 72;
    let lines;
    do {
        ctx.font = `700 ${fontSize}px Amiri`;
        lines = wrapLines(ctx, quote.content, w - 200);
        fontSize -= 4;
    } while (lines.length * fontSize * 1.75 > 620 && fontSize > 36);
    fontSize += 4;

    const lineHeight = fontSize * 1.75;
    const blockHeight = lines.length * lineHeight;
    let y = h / 2 - blockHeight / 2 + fontSize * 0.6 + 40;

    ctx.fillStyle = style.text;
    lines.forEach((line) => {
        ctx.fillText(line, w / 2, y);
        y += lineHeight;
    });

    if (quote.author) {
        ctx.font = '700 44px Amiri';
        ctx.fillStyle = style.accent;
        ctx.fillText(`— ${quote.author}`, w / 2, y + 40);
    }

    ctx.font = '500 30px "IBM Plex Sans Arabic"';
    ctx.fillStyle = style.muted;
    ctx.fillText(`شاركه ${quote.sharer}`, w / 2, h - 170);

    ctx.font = '700 56px "Reem Kufi"';
    ctx.fillStyle = style.text;
    ctx.fillText('صدى', w / 2 + 30, h - 85);
    ctx.fillStyle = style.accent;
    ctx.beginPath();
    ctx.arc(w / 2 - 100, h - 103, 14, 0, Math.PI * 2);
    ctx.fill();
    ctx.lineWidth = 3;
    ctx.strokeStyle = style.accent;
    ctx.beginPath();
    ctx.arc(w / 2 - 100, h - 103, 26, 0, Math.PI * 2);
    ctx.stroke();
}

function initQuoteImages() {
    const modal = document.getElementById('image-modal');
    if (!modal) {
        return;
    }
    const canvas = modal.querySelector('[data-image-canvas]');

    document.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-make-image]');
        if (trigger) {
            currentImageQuote = quoteDataFrom(trigger);
            drawQuoteImage(canvas, currentImageQuote, currentImageStyle);
            openModal(modal);
        }
        const swatch = event.target.closest('[data-image-style]');
        if (swatch && currentImageQuote) {
            currentImageStyle = swatch.dataset.imageStyle;
            modal.querySelectorAll('[data-image-style]').forEach((s) => s.classList.toggle('is-selected', s === swatch));
            drawQuoteImage(canvas, currentImageQuote, currentImageStyle);
        }
        if (event.target.closest('[data-image-download]')) {
            const link = document.createElement('a');
            link.download = `sada-quote-${Date.now()}.png`;
            link.href = canvas.toDataURL('image/png');
            link.click();
            toast('تم تحميل الصورة 🖼️');
        }
    });
}

/* ------------------------------------------------------------------ */
/* Scroll reveal, counters, typewriter                                  */
/* ------------------------------------------------------------------ */

function initReveal() {
    const items = document.querySelectorAll('[data-reveal]');
    if (!('IntersectionObserver' in window) || prefersReducedMotion) {
        items.forEach((el) => el.classList.add('is-visible'));
        return;
    }
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -40px 0px' },
    );
    items.forEach((el) => observer.observe(el));
}

function initCountUp() {
    const items = document.querySelectorAll('[data-count-to]');
    const formatter = new Intl.NumberFormat('ar-SA-u-nu-latn');
    const run = (el) => {
        const target = Number(el.dataset.countTo);
        const duration = prefersReducedMotion ? 0 : 1600;
        const start = performance.now();
        const step = (now) => {
            const progress = duration ? Math.min((now - start) / duration, 1) : 1;
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = formatter.format(Math.round(target * eased));
            if (progress < 1) {
                requestAnimationFrame(step);
            }
        };
        requestAnimationFrame(step);
    };
    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                run(entry.target);
                observer.unobserve(entry.target);
            }
        });
    });
    items.forEach((el) => observer.observe(el));
}

function initTypewriter() {
    document.querySelectorAll('[data-typewriter]').forEach((el) => {
        if (prefersReducedMotion) {
            return;
        }
        const chars = [...el.dataset.typewriter];
        el.textContent = '';
        el.classList.add('typing-caret');
        const observer = new IntersectionObserver((entries) => {
            if (!entries[0].isIntersecting) {
                return;
            }
            observer.disconnect();
            let i = 0;
            const tick = () => {
                el.textContent = chars.slice(0, ++i).join('');
                if (i < chars.length) {
                    setTimeout(tick, 45);
                } else {
                    setTimeout(() => el.classList.remove('typing-caret'), 1500);
                }
            };
            tick();
        });
        observer.observe(el);
    });
}

/* ------------------------------------------------------------------ */
/* Hero: echo rings canvas                                              */
/* ------------------------------------------------------------------ */

function initEchoCanvas() {
    document.querySelectorAll('[data-echo-canvas]').forEach((canvas) => {
        const ctx = canvas.getContext('2d');
        const small = canvas.hasAttribute('data-echo-small');
        const rings = [];
        const particles = [];
        let width = 0;
        let height = 0;
        let dpr = 1;
        let lastSpawn = 0;
        let pointer = null;

        const resize = () => {
            dpr = Math.min(window.devicePixelRatio || 1, 2);
            width = canvas.offsetWidth;
            height = canvas.offsetHeight;
            canvas.width = width * dpr;
            canvas.height = height * dpr;
            ctx.setTransform(dpr, 0, 0, dpr, 0, 0);
        };
        resize();
        window.addEventListener('resize', resize);

        for (let i = 0; i < (small ? 20 : 60); i++) {
            particles.push({
                x: Math.random(),
                y: Math.random(),
                r: Math.random() * 1.6 + 0.4,
                speed: Math.random() * 0.00025 + 0.00008,
                phase: Math.random() * Math.PI * 2,
            });
        }

        const spawn = (x, y, strength = 1) => rings.push({ x, y, radius: 0, alpha: 0.55 * strength, speed: 1.1 + strength * 0.6 });

        if (!small) {
            canvas.parentElement.addEventListener('pointerdown', (event) => {
                const rect = canvas.getBoundingClientRect();
                spawn(event.clientX - rect.left, event.clientY - rect.top, 1.4);
            });
            canvas.parentElement.addEventListener('pointermove', (event) => {
                const rect = canvas.getBoundingClientRect();
                pointer = { x: event.clientX - rect.left, y: event.clientY - rect.top };
            });
        }

        const accent = () => getComputedStyle(document.documentElement).getPropertyValue('--accent').trim() || '#4fd1c5';

        const frame = (now) => {
            ctx.clearRect(0, 0, width, height);
            const color = accent();
            const cx = width / 2;
            const cy = small ? height / 2 : height * 0.42;

            if (now - lastSpawn > (small ? 1600 : 1100)) {
                spawn(cx, cy);
                lastSpawn = now;
            }

            ctx.strokeStyle = color;
            for (let i = rings.length - 1; i >= 0; i--) {
                const ring = rings[i];
                ring.radius += ring.speed;
                ring.alpha *= 0.992;
                if (ring.alpha < 0.01 || ring.radius > Math.max(width, height)) {
                    rings.splice(i, 1);
                    continue;
                }
                ctx.globalAlpha = ring.alpha;
                ctx.lineWidth = 1.5;
                ctx.beginPath();
                ctx.arc(ring.x, ring.y, ring.radius, 0, Math.PI * 2);
                ctx.stroke();
            }

            ctx.fillStyle = color;
            particles.forEach((p) => {
                p.y -= p.speed;
                if (p.y < 0) {
                    p.y = 1;
                    p.x = Math.random();
                }
                let px = p.x * width + Math.sin(now / 2000 + p.phase) * 12;
                let py = p.y * height;
                if (pointer) {
                    const dx = px - pointer.x;
                    const dy = py - pointer.y;
                    const dist = Math.hypot(dx, dy);
                    if (dist < 120) {
                        px += (dx / dist) * (120 - dist) * 0.25;
                        py += (dy / dist) * (120 - dist) * 0.25;
                    }
                }
                ctx.globalAlpha = 0.25 + Math.sin(now / 900 + p.phase) * 0.2;
                ctx.beginPath();
                ctx.arc(px, py, p.r, 0, Math.PI * 2);
                ctx.fill();
            });
            ctx.globalAlpha = 1;

            if (!prefersReducedMotion) {
                requestAnimationFrame(frame);
            }
        };
        requestAnimationFrame(frame);
    });
}

/* ------------------------------------------------------------------ */
/* Misc                                                                 */
/* ------------------------------------------------------------------ */

function initMisc() {
    document.querySelectorAll('[data-auto-dismiss]').forEach((el) => dismissLater(el, 4200));
    document.querySelectorAll('[data-auto-submit]').forEach((select) => {
        select.addEventListener('change', () => select.form.submit());
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    initNavigation();
    initModals();
    initCounters();
    initEngagement();
    initSharing();
    initOwnerActions();
    initQuoteImages();
    initReveal();
    initCountUp();
    initTypewriter();
    initEchoCanvas();
    initMisc();
});
