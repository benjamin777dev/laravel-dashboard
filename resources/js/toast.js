 window.showToast=function(message, duration = 3000, backgroundColor = "#222", stopOnFocus = true) {
    Toastify({
        text: message,
        duration: duration,
        backgroundColor: backgroundColor,
        stopOnFocus: stopOnFocus
    }).showToast();
}

window.showToastError=function(message, duration = 3000, backgroundColor = "red", stopOnFocus = true) {
    Toastify({
        text: message,
        duration: duration,
        backgroundColor: backgroundColor,
        stopOnFocus: stopOnFocus
    }).showToast();
}



