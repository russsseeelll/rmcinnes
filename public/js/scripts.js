document.addEventListener('DOMContentLoaded', function() {
    const aboutCarousel = document.querySelector('#aboutCarousel');

    const indicators = document.querySelectorAll('.carousel-indicator');
    aboutCarousel.addEventListener('slide.bs.carousel', function(e) {
        indicators.forEach(indicator => indicator.classList.remove('active'));
        indicators[e.to].classList.add('active');
    });

    if (indicators.length > 0) {
        indicators[0].classList.add('active');
    }

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

    form.addEventListener("submit", function(event) {
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        submitButton.disabled = true;

        sessionStorage.setItem('submitted', 'true');
    });

    if (sessionStorage.getItem('submitted') === 'true') {
        sessionStorage.removeItem('submitted');

        if (messageSent || document.querySelectorAll('.alert-danger').length > 0) {
            setTimeout(function() {
                document.querySelector("#contact-form").scrollIntoView({
                    behavior: 'smooth'
                });
            }, 100);
        }
    }
});
