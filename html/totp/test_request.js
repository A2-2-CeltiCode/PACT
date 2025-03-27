function validateTOTP() {
    let code = document.getElementById("code").value;
    let url = window.location.protocol + "//" + window.location.host + "/totp/validate.php"
    let request = new Request(url, {
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        method: "POST",
        body: `code=${code}`
    })
    fetch(request)
        .then((response) => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error("Unable");
            }
        }).then((response) => {
            console.log(response);
        });
}



function setSessionId() {
    let id = document.getElementById("code").value;
    let url = window.location.protocol + "//" + window.location.host + "/totp/panel.php"
    let request = new Request(url, {
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        method: "POST",
        body: `id=${id}`
    })
    fetch(request)
        .then((response) => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error("Unable");
            }
        }).then((response) => {
            console.log(response);
        });
}

function generateTOTP() {
    let url = window.location.protocol + "//" + window.location.host + "/totp/generate.php"
    let request = new Request(url)
    fetch(request)
        .then((response) => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error("Unable");
            }
        }).then((response) => {
            console.log(response);
            document.getElementById("otpqrcode").src = response.qrcode;
        });
}

function validate() {
    let url = window.location.protocol + "//" + window.location.host + "/totp/generate.php"
    let request = new Request(url, {
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        method: "POST",
        body: `valid=${true}`
    })
    fetch(request)
        .then((response) => {
            if (response.ok) {
                return response.json();
            } else {
                throw new Error("Unable");
            }
        }).then((response) => {
            console.log(response);
            // document.getElementById("otpqrcode").src = response.qrcode;
        });
}