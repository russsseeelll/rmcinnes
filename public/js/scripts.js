//carousel
    document.querySelector('#aboutCarousel').addEventListener('mouseenter', function() {
    document.querySelector('.carousel-control-prev').style.display = 'block';
    document.querySelector('.carousel-control-next').style.display = 'block';
});

    document.querySelector('#aboutCarousel').addEventListener('mouseleave', function() {
    document.querySelector('.carousel-control-prev').style.display = 'none';
    document.querySelector('.carousel-control-next').style.display = 'none';
});

    document.addEventListener('DOMContentLoaded', function() {
    const aboutHeading = document.querySelector('.display-4');
    let i = 0;
    const txt = aboutHeading.textContent;
    aboutHeading.textContent = '';
    function typeWriter() {
    if (i < txt.length) {
    aboutHeading.textContent += txt.charAt(i);
    i++;
    setTimeout(typeWriter, 50);
}
}
    typeWriter();

    // Additional Page Load Effect: Fade-in text
    const subHeadings = document.querySelectorAll('.about-sub-heading');
    subHeadings.forEach(el => {
    el.style.opacity = 0;
    setTimeout(() => {
    el.style.transition = 'opacity 2s';
    el.style.opacity = 1;
}, 500);
});
});
