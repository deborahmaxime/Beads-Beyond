const toggle = document.getElementById('darkToggle');

// Load saved preference
if (localStorage.getItem('theme') === 'dark') {
    document.body.classList.add('dark-mode');
    toggle.textContent = 'Light';
}

toggle.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode');

    // Save preference
    if (document.body.classList.contains('dark-mode')) {
        localStorage.setItem('theme', 'dark');
        toggle.textContent = 'Light';
    } else {
        localStorage.setItem('theme', 'light');
        toggle.textContent = 'Dark';
    }
});
