var checkbox = document.getElementById('confirm-subscribe-sidebar');

checkbox.onchange = function() {
    var buttonSubscribe = document.querySelector('button[data-id="confirm-subscribe-sidebar"]');
    if (checkbox.checked === true) {
        buttonSubscribe.removeAttribute('disabled');
    } else {
        buttonSubscribe.setAttribute('disabled', 'disabled');
    }
};