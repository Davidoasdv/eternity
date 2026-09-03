document.addEventListener('DOMContentLoaded', function() {
    const startDate = '2023-11-02';
    const start = new Date(startDate + 'T00:00:00+07:00');

    function updateCounter() {
        const now = new Date();
        const diff = now - start;
        const seconds = Math.floor(diff / 1000);
        const days = Math.floor(seconds / 86400);
        const hours = Math.floor((seconds % 86400) / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;

        const daysEl = document.getElementById('counterDays');
        const hoursEl = document.getElementById('counterHours');
        const minutesEl = document.getElementById('counterMinutes');
        const secondsEl = document.getElementById('counterSeconds');

        if (daysEl) daysEl.textContent = days.toLocaleString() + ' DAYS';
        if (hoursEl) hoursEl.textContent = String(hours).padStart(2, '0') + ' HOURS';
        if (minutesEl) minutesEl.textContent = String(minutes).padStart(2, '0') + ' MINUTES';
        if (secondsEl) secondsEl.textContent = String(secs).padStart(2, '0') + ' SECONDS';
    }

    updateCounter();
    setInterval(updateCounter, 1000);
});