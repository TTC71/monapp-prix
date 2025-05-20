
document.querySelector("form").addEventListener("submit", function(e) {
    e.preventDefault();

    const username = document.querySelector('input[name="username"]').value;
    const password = document.querySelector('input[name="password"]').value;
    const remember = document.querySelector('input[name="remember"]').checked;

    const formData = new FormData();
    formData.append("username", username);
    formData.append("password", password);
    formData.append("remember", remember);

    fetch('http://192.168.1.16/scan_pro_api/login.php', {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.token) {
            localStorage.setItem("token", data.token);
            window.location.href = data.role === "admin" ? "admin_dashboard.php" : "user_dashboard.php";
        } else {
            alert(data.error);
        }
    })
    .catch(error => {
        alert("Erreur de connexion au serveur.");
    });
});
