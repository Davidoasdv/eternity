function openModal(htmlContent) {
    const overlay = document.createElement('div');
    overlay.className = 'modal-overlay';
    overlay.innerHTML = `
        <div class="modal-content">
            ${htmlContent}
            <button class="btn btn-secondary" onclick="this.closest('.modal-overlay').remove()">Close</button>
        </div>
    `;
    document.body.appendChild(overlay);
}