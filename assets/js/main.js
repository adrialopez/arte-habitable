/* ============================================
   Arte Habitable — WordPress Theme JS
   ============================================ */

document.addEventListener('DOMContentLoaded', () => {

    // ── Navigation ──────────────────────────
    const nav = document.getElementById('nav');
    const navToggle = document.getElementById('navToggle');
    const navMenu = document.getElementById('navMenu');

    if (nav) {
        window.addEventListener('scroll', () => {
            nav.classList.toggle('nav--scrolled', window.scrollY > 80);
        }, { passive: true });
    }

    if (navToggle && navMenu) {
        navToggle.addEventListener('click', () => {
            navToggle.classList.toggle('active');
            navMenu.classList.toggle('active');
            document.body.style.overflow = navMenu.classList.contains('active') ? 'hidden' : '';
        });

        navMenu.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navToggle.classList.remove('active');
                navMenu.classList.remove('active');
                document.body.style.overflow = '';
            });
        });
    }

    // ── Hero Slider ─────────────────────────
    const slides = document.querySelectorAll('.hero__slide');
    let currentSlide = 0;

    function preloadSlide(index) {
        const img = slides[index].querySelector('img');
        if (img && img.loading === 'lazy') {
            img.loading = 'eager';
        }
    }

    if (slides.length > 1) {
        setTimeout(() => preloadSlide(1), 1000);

        setInterval(() => {
            slides[currentSlide].classList.remove('active');
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].classList.add('active');
            preloadSlide((currentSlide + 1) % slides.length);
        }, 5000);
    }

    // ── Reveal on Scroll ────────────────────
    const revealElements = document.querySelectorAll('.reveal');

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                revealObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.15,
        rootMargin: '0px 0px -50px 0px'
    });

    revealElements.forEach(el => revealObserver.observe(el));

    // ── Portafolio Horizontal Scroll Carousel ───────────────────
    const portafolioWrapper = document.querySelector('.portafolio-wrapper');
    const projectsTrack     = document.querySelector('.projects-track');

    if (portafolioWrapper && projectsTrack) {
        const slides        = projectsTrack.querySelectorAll('.project-slide');
        const count         = slides.length;
        const storyFills    = document.querySelectorAll('.portafolio__story-fill');
        const counterEl     = document.querySelector('.portafolio__counter-current');
        const prevBtn       = document.querySelector('.portafolio__nav--prev');
        const nextBtn       = document.querySelector('.portafolio__nav--next');
        const isMobile      = window.matchMedia('(max-width: 768px)').matches;
        let currentSlide    = 0;

        function updateUI(slideIndex, fillProgress) {
            // fillProgress: 0-1 within the current slide (for stories)
            storyFills.forEach((fill, i) => {
                if (i < slideIndex) {
                    fill.style.width = '100%';
                } else if (i === slideIndex) {
                    fill.style.width = (fillProgress * 100).toFixed(1) + '%';
                } else {
                    fill.style.width = '0%';
                }
            });

            if (counterEl) {
                counterEl.textContent = String(slideIndex + 1).padStart(2, '0');
            }

            if (prevBtn) prevBtn.classList.toggle('disabled', slideIndex === 0);
            if (nextBtn) nextBtn.classList.toggle('disabled', slideIndex === count - 1);
        }

        // ── Desktop: scroll-driven ──
        if (!isMobile) {
            let cachedWrapperTop = null;

            function getWrapperTop() {
                if (cachedWrapperTop === null) {
                    cachedWrapperTop = portafolioWrapper.getBoundingClientRect().top + window.scrollY;
                }
                return cachedWrapperTop;
            }

            window.addEventListener('resize', () => { cachedWrapperTop = null; }, { passive: true });

            function onScroll() {
                const wrapperTop  = getWrapperTop();
                const scrollInto  = window.scrollY - wrapperTop;
                const maxScroll   = portafolioWrapper.offsetHeight - window.innerHeight;
                const progress    = Math.max(0, Math.min(1, scrollInto / maxScroll));

                // Continuous translate (no snapping — smooth like parallax)
                const rawIndex    = progress * (count - 1);
                const maxTranslate = Math.max(0, projectsTrack.offsetWidth - window.innerWidth);
                const translateX  = progress * maxTranslate;
                projectsTrack.style.transform = `translateX(-${translateX}px)`;

                // UI: nearest slide + fill within that slide
                const slideIndex  = Math.min(count - 1, Math.floor(rawIndex));
                const fillPct     = rawIndex - slideIndex; // 0-1 within this slide
                currentSlide      = Math.round(rawIndex);
                updateUI(slideIndex, fillPct);
            }

            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();

            // Arrow/dot clicks scroll the page to the target position
            function scrollToSlide(index) {
                const wrapperTop = getWrapperTop();
                const maxScroll  = portafolioWrapper.offsetHeight - window.innerHeight;
                const target     = wrapperTop + (index / (count - 1)) * maxScroll;
                window.scrollTo({ top: target, behavior: 'smooth' });
            }

            if (prevBtn) prevBtn.addEventListener('click', () => scrollToSlide(Math.max(0, currentSlide - 1)));
            if (nextBtn) nextBtn.addEventListener('click', () => scrollToSlide(Math.min(count - 1, currentSlide + 1)));

        // ── Mobile: touch swipe ──
        } else {
            function goToSlide(index) {
                currentSlide = Math.max(0, Math.min(count - 1, index));
                const cardStep = slides[0].offsetWidth + 16; // card width + 1rem gap
                const maxTranslate = Math.max(0, projectsTrack.offsetWidth - window.innerWidth);
                const translateX = Math.min(currentSlide * cardStep, maxTranslate);
                projectsTrack.style.transform = `translateX(-${translateX}px)`;
                updateUI(currentSlide, currentSlide === count - 1 ? 1 : 0);
            }

            if (prevBtn) prevBtn.addEventListener('click', () => goToSlide(currentSlide - 1));
            if (nextBtn) nextBtn.addEventListener('click', () => goToSlide(currentSlide + 1));

            let touchStartX = 0;
            let touchStartY = 0;

            projectsTrack.addEventListener('touchstart', (e) => {
                touchStartX = e.touches[0].clientX;
                touchStartY = e.touches[0].clientY;
            }, { passive: true });

            projectsTrack.addEventListener('touchend', (e) => {
                const dx = touchStartX - e.changedTouches[0].clientX;
                const dy = touchStartY - e.changedTouches[0].clientY;
                if (Math.abs(dx) > Math.abs(dy) && Math.abs(dx) > 45) {
                    goToSlide(dx > 0 ? currentSlide + 1 : currentSlide - 1);
                }
            }, { passive: true });

            goToSlide(0);
        }
    }

    // ── Project Modal ───────────────────────
    const modal = document.getElementById('projectModal');
    if (modal) {
        const modalGallery = document.getElementById('modalGallery');
        const modalInfo = document.getElementById('modalInfo');
        const modalClose = modal.querySelector('.modal__close');

        // "Vista rápida" button triggers the slide's click (modal)
        document.querySelectorAll('.btn-quickview').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.stopPropagation();
                btn.closest('.project-slide').click();
            });
        });

        // Works for .project (old grid), .project-slide (carousel) and .archive-card
        document.querySelectorAll('.project, .project-slide, .archive-card').forEach(card => {
            card.addEventListener('click', (e) => {
                // Don't open modal if clicking the "Ver proyecto" link
                if (e.target.closest('.project-slide__link')) return;
                // Don't open modal if clicking the detail link on archive
                if (e.target.closest('.archive-card__detail')) return;

                const title = card.dataset.title;
                const location = card.dataset.location;
                const description = card.dataset.description;
                const gallery = JSON.parse(card.dataset.gallery || '[]');
                const permalink = card.dataset.permalink;

                if (!title) return;

                let infoHTML = `
                    <h2>${title}</h2>
                    <p class="modal__location">${location}</p>
                    ${description.split('\n\n').map(p => `<p>${p}</p>`).join('')}
                `;

                if (permalink) {
                    infoHTML += `
                        <a href="${permalink}" class="modal__permalink">
                            <span>Ver proyecto completo</span>
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                        </a>
                    `;
                }

                modalInfo.innerHTML = infoHTML;

                modalGallery.innerHTML = gallery
                    .map(src => `<img src="${src}" alt="${title}" loading="lazy">`)
                    .join('');

                modal.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        });

        if (modalClose) {
            modalClose.addEventListener('click', closeModal);
        }
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('active')) closeModal();
        });

        function closeModal() {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    // ── Archive Filters ─────────────────────
    const filters = document.querySelectorAll('.archive-filter');
    const archiveCards = document.querySelectorAll('.archive-card');

    if (filters.length > 0) {
        filters.forEach(btn => {
            btn.addEventListener('click', () => {
                const filter = btn.dataset.filter;

                filters.forEach(f => f.classList.remove('active'));
                btn.classList.add('active');

                archiveCards.forEach(card => {
                    const cat = card.dataset.category;
                    if (filter === 'all' || cat === filter) {
                        card.style.display = '';
                        requestAnimationFrame(() => {
                            card.classList.remove('archive-card--hidden');
                        });
                    } else {
                        card.classList.add('archive-card--hidden');
                        setTimeout(() => {
                            if (card.classList.contains('archive-card--hidden')) {
                                card.style.display = 'none';
                            }
                        }, 400);
                    }
                });
            });
        });
    }

    // ── Image Lightbox (single proyecto) ────
    const galleryItems = document.querySelectorAll('.proyecto-gallery__item img');

    if (galleryItems.length > 0) {
        const lightbox = document.createElement('div');
        lightbox.className = 'lightbox';
        lightbox.innerHTML = `
            <button class="lightbox__close" aria-label="Cerrar">&times;</button>
            <button class="lightbox__prev" aria-label="Anterior">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
            </button>
            <button class="lightbox__next" aria-label="Siguiente">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>
            </button>
            <div class="lightbox__img-wrap">
                <img class="lightbox__img" src="" alt="">
            </div>
            <div class="lightbox__counter"></div>
        `;
        document.body.appendChild(lightbox);

        const lbImg = lightbox.querySelector('.lightbox__img');
        const lbCounter = lightbox.querySelector('.lightbox__counter');
        let lbIndex = 0;
        const lbSources = Array.from(galleryItems).map(img => img.src);

        function showLightbox(index) {
            lbIndex = index;
            lbImg.src = lbSources[index];
            lbCounter.textContent = (index + 1) + ' / ' + lbSources.length;
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            lightbox.classList.remove('active');
            document.body.style.overflow = '';
        }

        galleryItems.forEach((img, i) => {
            img.style.cursor = 'pointer';
            img.addEventListener('click', () => showLightbox(i));
        });

        lightbox.querySelector('.lightbox__close').addEventListener('click', closeLightbox);
        lightbox.querySelector('.lightbox__prev').addEventListener('click', () => {
            showLightbox((lbIndex - 1 + lbSources.length) % lbSources.length);
        });
        lightbox.querySelector('.lightbox__next').addEventListener('click', () => {
            showLightbox((lbIndex + 1) % lbSources.length);
        });
        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox || e.target.classList.contains('lightbox__img-wrap')) closeLightbox();
        });
        document.addEventListener('keydown', (e) => {
            if (!lightbox.classList.contains('active')) return;
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') showLightbox((lbIndex - 1 + lbSources.length) % lbSources.length);
            if (e.key === 'ArrowRight') showLightbox((lbIndex + 1) % lbSources.length);
        });
    }

    // ── Smooth scroll & anchor navigation ───
    const isHome = document.body.classList.contains('home') || document.body.classList.contains('page-template-front-page');
    const homeUrl = document.querySelector('.nav__logo')?.href || '/';

    document.querySelectorAll('a[href*="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            if (href === '#') return;

            // Extract the hash part
            let hash;
            try {
                const url = new URL(href, window.location.origin);
                hash = url.hash;
                // If the link points to a different page, let the browser handle it
                if (url.pathname !== window.location.pathname && url.pathname !== '/') return;
            } catch {
                hash = href.startsWith('#') ? href : null;
            }

            if (!hash) return;

            // If on home, smooth scroll to section
            if (isHome) {
                const target = document.querySelector(hash);
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            } else {
                // Not on home: redirect to home + hash
                e.preventDefault();
                window.location.href = homeUrl + hash;
            }
        });
    });

});
