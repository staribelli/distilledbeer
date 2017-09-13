var Beer = function () {
    var selectors = {
        random: '#random',
        more: '#more',
        beerName: '#name',
        description: '#description',
        image: '#avatar',
        loader: '#loader'
    };

    var randomHandler = function () {
        $(selectors.loader).show();

        $.ajax({
            url: "/index.php/random",
            success: function (data) {
                $(selectors.beerName).text(data.name);
                $(selectors.description).text(data.description);
                $(selectors.image).attr('src', data.imgPath);
            }
        }).done(function () {
            $(selectors.loader).hide();
        });
    };

    var moreHandler = function () {
        console.log('more');
    };

    var bindEvents = function () {
        $(selectors.random).on('click', function (e) {
            e.preventDefault();
            randomHandler();
        });
        $(selectors.more).on('click', function (e) {
            e.preventDefault();
            moreHandler();
        });
    };

    var init = function () {
        bindEvents();
    };

    return {
        init: init
    }
};

$(document).ready(function () {
    var beer = Beer();
    beer.init();
});