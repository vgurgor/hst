import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

if (localStorage.theme === 'dark') {
    document.documentElement.classList.add('dark');
    document.getElementById("theme-toggle").checked = true;
} else {
    document.documentElement.classList.remove('dark');
    document.getElementById("theme-toggle").checked = false;
}
document.getElementById('theme-toggle').addEventListener('change', function () {
    // On page load or when changing themes, best to add inline in `head` to avoid FOUC
    if (localStorage.theme === 'dark') {
        document.documentElement.classList.remove('dark');
        localStorage.theme = 'light';
    } else {
        document.documentElement.classList.add('dark');
        localStorage.theme = 'dark';
    }
});


