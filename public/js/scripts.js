

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

document.addEventListener("DOMContentLoaded", function() {
    const form = document.querySelector(".contact-form-card form");
    const submitButton = form.querySelector("button[type='submit']");
    const originalButtonHtml = submitButton.innerHTML;
    const messageSent = "{{ session('message_sent') }}" || false;

    form.addEventListener("submit", function() {
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        submitButton.disabled = true;
    });

    // Scroll to the form on page load if 'message_sent' or errors exist
    if (messageSent || document.querySelectorAll('.alert-danger').length > 0) {
        setTimeout(function() {
            document.querySelector("#contact-form").scrollIntoView({behavior: 'smooth'});
        }, 100);
    }
});


