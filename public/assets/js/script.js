$(document).ready(function () {
    function goBack() {
        window.history.back();
    }

    $('.back').on('click', () => {
        goBack();
    });

});