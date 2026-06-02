/* ============================================
   JACHA TOURS - SCRIPT COMPLETO (CORREGIDO)
   Preloader funcional, rendimiento optimizado
   ============================================ */

// ========== PRELOADER CORREGIDO ==========
(function() {
    // Esperar a que el DOM esté listo
    const initPreloader = () => {
        const preloader = document.getElementById('preloader');
        const fillBar = document.getElementById('preloaderFill');
        
        // Si no existe el preloader, salir
        if (!preloader) {
            console.log('Preloader no encontrado, continuando...');
            document.body.style.overflow = 'auto';
            return;
        }
        
        console.log('Preloader inicializado');
        
        let isHidden = false;
        let pageLoaded = false;
        let minTimePassed = false;
        
        const hidePreloader = () => {
            if (isHidden) return;
            isHidden = true;
            
            console.log('Ocultando preloader...');
            preloader.style.opacity = '0';
            preloader.style.transition = 'opacity 0.5s ease';
            
            setTimeout(() => {
                preloader.style.display = 'none';
                preloader.classList.add('hidden');
                document.body.style.overflow = 'auto';
                document.body.style.height = 'auto';
                window.dispatchEvent(new Event('preloaderComplete'));
                console.log('Preloader oculto completamente');
            }, 500);
        };
        
        // Tiempo mínimo de 2 segundos
        setTimeout(() => {
            console.log('Tiempo mínimo (2s) cumplido');
            minTimePassed = true;
            if (pageLoaded) hidePreloader();
        }, 2000);
        
        // Barra de progreso
        if (fillBar) {
            let progress = 0;
            const interval = setInterval(() => {
                progress += Math.random() * 8 + 3;
                if (progress >= 100) {
                    progress = 100;
                    clearInterval(interval);
                }
                fillBar.style.width = progress + '%';
            }, 50);
        }
        
        // Cuando la página carga completamente
        window.addEventListener('load', () => {
            console.log('Página completamente cargada');
            pageLoaded = true;
            if (fillBar) {
                fillBar.style.width = '100%';
            }
            if (minTimePassed) hidePreloader();
        });
        
        // Fallback: ocultar después de 4 segundos máximo (por si algo falla)
        setTimeout(() => {
            if (!isHidden) {
                console.log('Fallback: ocultando preloader por timeout');
                hidePreloader();
            }
        }, 4000);
    };
    
    // Iniciar cuando el DOM esté listo
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPreloader);
    } else {
        initPreloader();
    }
})();

// ========== HEADER SCROLL CON DEBOUNCE ==========
const header = document.getElementById('header');
let scrollTimeout;

if (header) {
    window.addEventListener('scroll', () => {
        if (scrollTimeout) clearTimeout(scrollTimeout);
        
        scrollTimeout = setTimeout(() => {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }, 10);
    });
}

// ========== HAMBURGER MENU MEJORADO ==========
const hamburger = document.getElementById('hamburger');
const navMenu = document.getElementById('navMenu');

if (hamburger && navMenu) {
    const toggleMenu = () => {
        hamburger.classList.toggle('active');
        navMenu.classList.toggle('open');
        document.body.style.overflow = navMenu.classList.contains('open') ? 'hidden' : '';
        
        const links = navMenu.querySelectorAll('.nav-link');
        if (navMenu.classList.contains('open')) {
            links.forEach((link, index) => {
                link.style.animation = `fadeInNav 0.3s ease forwards ${index * 0.05}s`;
            });
        } else {
            links.forEach(link => {
                link.style.animation = '';
            });
        }
    };
    
    hamburger.addEventListener('click', toggleMenu);
    
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', () => {
            if (navMenu.classList.contains('open')) {
                hamburger.classList.remove('active');
                navMenu.classList.remove('open');
                document.body.style.overflow = '';
            }
        });
    });
    
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && navMenu.classList.contains('open')) {
            hamburger.classList.remove('active');
            navMenu.classList.remove('open');
            document.body.style.overflow = '';
        }
    });
}

// ========== VIDEO CON MEJOR MANEJO ==========
const video = document.querySelector('.hero__video');

if (video) {
    const initVideo = () => {
        video.muted = true;
        video.setAttribute('playsinline', '');
        
        const playPromise = video.play();
        if (playPromise !== undefined) {
            playPromise.catch(error => {
                console.log('Autoplay prevented:', error);
            });
        }
    };
    
    window.addEventListener('preloaderComplete', initVideo);
    if (document.readyState === 'complete') {
        setTimeout(initVideo, 100);
    } else {
        window.addEventListener('load', initVideo);
    }
}

// ========== SMOOTH SCROLL MEJORADO ==========
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const targetId = this.getAttribute('href');
        
        if (targetId === '#' || targetId === '#login' || targetId === '#loginModal') return;
        
        const target = document.querySelector(targetId);
        if (target) {
            e.preventDefault();
            const headerOffset = 80;
            const elementPosition = target.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;
            
            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
            
            if (history.pushState) {
                history.pushState(null, null, targetId);
            }
        }
    });
});

// ========== ACTIVE NAV LINK MEJORADO ==========
const sections = document.querySelectorAll('section[id]');
const navLinks = document.querySelectorAll('.nav-link');

if (sections.length && navLinks.length) {
    let ticking = false;
    
    const updateActiveLink = () => {
        let current = '';
        const scrollPosition = window.scrollY + 120;
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                current = section.getAttribute('id');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            const href = link.getAttribute('href').substring(1);
            if (href === current) {
                link.classList.add('active');
            }
        });
        
        ticking = false;
    };
    
    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(updateActiveLink);
            ticking = true;
        }
    });
    
    updateActiveLink();
}

// ========== ANIMACIONES AL SCROLL ==========
const revealElements = document.querySelectorAll('.hero__content, .experiencia-card, .circuito-item, .cholet-card');

const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInNav {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
    
    .hero__content, .experiencia-card, .circuito-item, .cholet-card {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .hero__content.revealed, .experiencia-card.revealed, .circuito-item.revealed, .cholet-card.revealed {
        opacity: 1;
        transform: translateY(0);
    }
    
    .error-message {
        animation: shake 0.3s ease;
    }
    
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
`;

document.head.appendChild(style);

if ('IntersectionObserver' in window) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('revealed');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });
    
    revealElements.forEach(element => observer.observe(element));
} else {
    const revealOnScroll = () => {
        revealElements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            const elementVisible = 150;
            if (elementTop < window.innerHeight - elementVisible) {
                element.classList.add('revealed');
            }
        });
    };
    window.addEventListener('scroll', revealOnScroll);
    revealOnScroll();
}

// ========== BOTÓN DE EXPLORAR ==========
const exploreBtn = document.querySelector('.btn--primary');
if (exploreBtn) {
    exploreBtn.addEventListener('click', (e) => {
        const target = document.querySelector('#circuito');
        if (target) {
            e.preventDefault();
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
}

// ========== LOGO AL INICIO ==========
const logo = document.querySelector('.header__logo');
if (logo) {
    logo.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

// ========== EFECTO TILT PARA TARJETAS (OPTIMIZADO) ==========
(function() {
    const MAX_TILT = 14;
    
    const cards = document.querySelectorAll('[data-tilt]');
    
    if (cards.length === 0) return;
    
    console.log(`Inicializando efecto Tilt en ${cards.length} tarjetas`);
    
    cards.forEach(card => {
        // Asegurar que el card-shine existe
        let shine = card.querySelector('.card-shine');
        if (!shine) {
            shine = document.createElement('div');
            shine.className = 'card-shine';
            shine.style.cssText = `
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                border-radius: inherit;
                pointer-events: none;
                opacity: 0;
                transition: opacity 0.3s ease;
                z-index: 2;
            `;
            if (getComputedStyle(card).position === 'static') {
                card.style.position = 'relative';
            }
            card.style.overflow = 'hidden';
            card.style.transformStyle = 'preserve-3d';
            card.appendChild(shine);
        }
        
        card.addEventListener('mouseenter', () => {
            card.style.transition = 'transform 0.1s ease, box-shadow 0.3s ease';
            if (shine) shine.style.opacity = '1';
        });
        
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const cx = rect.left + rect.width / 2;
            const cy = rect.top + rect.height / 2;
            const dx = (e.clientX - cx) / (rect.width / 2);
            const dy = (e.clientY - cy) / (rect.height / 2);
            const rotateX = -(dy * MAX_TILT);
            const rotateY = dx * MAX_TILT;
            
            card.style.transform = `perspective(900px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.04, 1.04, 1.04)`;
            
            if (shine) {
                const px = ((e.clientX - rect.left) / rect.width * 100).toFixed(1);
                const py = ((e.clientY - rect.top) / rect.height * 100).toFixed(1);
                shine.style.background = `radial-gradient(circle at ${px}% ${py}%, rgba(255,255,255,0.12) 0%, transparent 70%)`;
            }
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transition = 'transform 0.5s cubic-bezier(0.23, 1, 0.32, 1), box-shadow 0.3s ease';
            card.style.transform = 'perspective(900px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)';
            if (shine) shine.style.opacity = '0';
        });
    });
})();

// ========== REDUCED MOTION ==========
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
if (prefersReducedMotion.matches) {
    const reducedStyle = document.createElement('style');
    reducedStyle.textContent = `
        *, *::before, *::after {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
            scroll-behavior: auto !important;
        }
    `;
    document.head.appendChild(reducedStyle);
}

// ========== CONSOLA ==========
console.log('%c✨ JACHA TOURS ✨', 'color: #C9A84C; font-size: 16px; font-weight: bold;');
console.log('%cCircuito de Cholets - El Alto, Bolivia', 'color: #fff; font-size: 12px;');
console.log('%cEfecto Tilt activado en ' + document.querySelectorAll('[data-tilt]').length + ' tarjetas', 'color: #4CAF50; font-size: 12px;');
console.log('%cPreloader: ' + (document.getElementById('preloader') ? '✅ Encontrado' : '❌ No encontrado'), 'color: #FFD700; font-size: 12px;');