document.addEventListener('DOMContentLoaded', (event) => {
    // flash message animation
    const flashMessage = document.querySelector('.cust-flash');

    if (flashMessage) {
        setTimeout(() => {
            flashMessage.classList.add('slide-out');

            flashMessage.addEventListener('animationend', () => {
                flashMessage.remove();
            });
        }, 5000);
    }
});