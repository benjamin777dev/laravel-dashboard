window.isValidUrl = function (url) {
    var regex = new RegExp(
        "^(https?:\\/\\/)" + // protocol
            "((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.?)+[a-z]{2,}|" + // domain name
            "((\\d{1,3}\\.){3}\\d{1,3})|" + // OR ip (v4) address
            "\\[?[a-f\\d]*:[a-f\\d:]+\\]?)" + // OR ip (v6) address
            "(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*" + // port and path
            "(\\?[;&a-z\\d%_.~+=-]*)?" + // query string
            "(\\#[-a-z\\d_]*)?$",
        "i" // fragment locator
    );
    return regex.test(url);
};

window.isValidEmail = function (email) {
    var regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
};
