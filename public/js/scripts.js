

document.addEventListener('DOMContentLoaded', function() {
    // Carousel mouse enter/leave to toggle controls
    const aboutCarousel = document.querySelector('#aboutCarousel');
    if (aboutCarousel) {
        aboutCarousel.addEventListener('mouseenter', function() {
            document.querySelectorAll('.carousel-control-prev, .carousel-control-next').forEach(control => control.style.display = 'block');
        });

        aboutCarousel.addEventListener('mouseleave', function() {
            document.querySelectorAll('.carousel-control-prev, .carousel-control-next').forEach(control => control.style.display = 'none');
        });
    }

    // Set indicators' opacity on mouse enter/leave
    const indicators = document.querySelectorAll('.carousel-indicator');
    if (aboutCarousel) {
        aboutCarousel.addEventListener('mouseenter', function() {
            indicators.forEach(indicator => indicator.style.opacity = '1');
        });

        aboutCarousel.addEventListener('mouseleave', function() {
            indicators.forEach(indicator => indicator.style.opacity = '0');
        });
    }

    // Typewriter effect
    const aboutHeading = document.querySelector('.display-4');
    if (aboutHeading) {
        let i = 0;
        const txt = aboutHeading.textContent;
        aboutHeading.textContent = '';
        function typeWriter() {
            if (i < txt.length) {
                aboutHeading.textContent += txt.charAt(i);
                i++;
                setTimeout(typeWriter, 80);
            }
        }
        typeWriter();
    }
});

