// Stations Page Modal Functions
function openEditModal(serial, name, description) {
    document.getElementById('edit_serial').value = serial;
    document.getElementById('edit_name').value = name || '';
    document.getElementById('edit_description').value = description || '';
    document.getElementById('editModal').style.display = 'block';
}

// Initialize modal close functionality
document.addEventListener('DOMContentLoaded', function() {
    // Close modal when "X" is clicked
    var closeBtn = document.querySelector('.close');
    if (closeBtn) {
        closeBtn.onclick = function() {
            document.getElementById('editModal').style.display = 'none';
        };
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
        var modal = document.getElementById('editModal');
        if (modal && event.target == modal) {
            modal.style.display = 'none';
        }
    };
});