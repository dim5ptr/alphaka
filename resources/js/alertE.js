// scripts.js
document.getElementById("emailForm").addEventListener("submit", function(event) {
    event.preventDefault();
    checkEmail();
});

function checkEmail() {
    var email = document.getElementById("email").value;
    var alertDiv = document.getElementById("alert");

    // Ganti URL_API_EMAIL dengan URL API yang sebenarnya
    var apiUrl = '/check-email'; // Adjust the route as per your Laravel setup

    fetch(apiUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (!data.exists) {
            alertDiv.style.display = 'block';
        } else {
            alertDiv.style.display = 'none';
            // Kirim form jika email tersedia
            document.getElementById("emailForm").submit();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
