// app.js
document.addEventListener('DOMContentLoaded', () => {
    // Mobile menu toggle
    const menuToggle = document.createElement('div');
    menuToggle.className = 'mobile-menu';
    menuToggle.innerHTML = '☰';
    document.querySelector('.navbar').appendChild(menuToggle);

    menuToggle.addEventListener('click', () => {
        document.querySelector('.nav-links').classList.toggle('active');
    });

    // Course enrollment handling
    document.querySelectorAll('.enroll-btn').forEach(button => {
        button.addEventListener('click', async (e) => {
            const courseId = e.target.dataset.courseId;
            const response = await fetch('/enroll', {
                method: 'POST',
                body: JSON.stringify({ courseId }),
                headers: {
                    'Content-Type': 'application/json'
                }
            });
            // Handle response
        });
    });
});