var intervalId;

function validateTOTP() {
    function leftFillNum(num, targetLength) {
		return num.toString().padStart(targetLength, "0");
	}

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
            if (response.valid) {
                const params = new Proxy(new URLSearchParams(window.location.search), {
                    get: (searchParams, prop) => searchParams.get(prop),
                });
                let url = window.location.protocol + "//" + window.location.host + "/membre/" + params.context ?? "accueil/accueil.php"
                window.location.replace(url)
            } else {
                let error = document.getElementById("expError")
                switch (response.reason) {
                    case "incorrect code":
                        error.textContent = "le code est incorrect"
                        break
                    
                    case "wait until":
                        function countdown() {
                            let timeleft = Math.round((new Date(response.until) - new Date()))
                            if (timeleft > 0) {
                                let HourLeft = Math.floor(timeleft / (60 * 60 * 1000))
                                let MinuteLeft = Math.floor(timeleft / (60 * 1000))
                                let SecondLeft = Math.floor(timeleft / (1000))
                                console.log(timeleft)
                                error.textContent = ` attendez ${leftFillNum(HourLeft, 2)}h ${leftFillNum(MinuteLeft, 2)}min ${leftFillNum(SecondLeft, 2)}s`
                            } else {
                                error.textContent = ''
                            }
                        }
                        countdown()
                        clearInterval(intervalId)
                        intervalId = setInterval(countdown, 1000)
                        break
                }
            }
        });
}

function validateForm(event) {
    if (event.key != 'Enter') {
        return false;
    } else if (document.getElementById("code").value.match(/^[0-9]{6}$/)) {
        event.preventDefault()
        validateTOTP();
    } else {
        event.preventDefault()
        return false;
    }
}