console.log('🥝', url);

const qrcode = new QRCode(document.getElementById("qr"), {
    text: url,
    width: 400,
    height: 400,
    colorDark: "#2fab00",
    colorLight: "#00170a",
    correctLevel: QRCode.CorrectLevel.L
});

function startTimer(duration, display) {
    let timer = duration, minutes, seconds;
    const c = setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);

        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.textContent = minutes + ":" + seconds;

        if (--timer < 0) {
            clearInterval(c);
        }
    }, 1000);
}


const display = document.querySelector('#timer');
if (display) {
    startTimer(seconds, display);
}

