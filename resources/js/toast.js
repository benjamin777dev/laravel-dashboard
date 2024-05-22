function showToast(message, duration = 3000, backgroundColor = "#222", stopOnFocus = true) {
    Toastify({
        text: message,
        duration: duration,
        backgroundColor: backgroundColor,
        stopOnFocus: stopOnFocus
    }).showToast();
}

function showToastError(message, duration = 3000, backgroundColor = "red", stopOnFocus = true) {
    Toastify({
        text: message,
        duration: duration,
        backgroundColor: backgroundColor,
        stopOnFocus: stopOnFocus
    }).showToast();
}



