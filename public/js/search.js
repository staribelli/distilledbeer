var Search = function () {
    var selectors = {
        form: '#search_form',
        search: 'input[name="search"]',
        type: 'input[name="type"]',
        errorsContainer: '#errors',
        listContainer: '#list',
        item: '.item',
        name: '.name'
    };

    var invalidHandler = function (errors) {
        var form = $(selectors.form);
        var errorsContainer = $(selectors.errorsContainer).text('');

        $.each(errors, function (fieldName, error) {
            var input = form.find(selectors[fieldName]);
            input.addClass('error');
            errorsContainer.append(errors[fieldName]);
        });

        errorsContainer.show();
    };

    var successHandler = function (data) {
        console.log(data);
        $.each(JSON.parse(data), function (key, el) {
            //var template = $(selectors.listContainer).find(selectors.item);
            //var item = template.clone();
            //item = item[0];
            //item.find(selectors.name).text(data.name);
            //console.log(item.outerHTML);

            //console.log(el);
        });
    };

    var submitHandler = function () {
        var form = $(selectors.form);
        var data = {
            'search': form.find(selectors.search).val(),
            'type': form.find(selectors.type + ':checked').val()
        };
        $(selectors.errorsContainer).hide();
        form.find('input').removeClass('error');

        $.ajax({
            url: "/index.php/search",
            method: 'POST',
            data: data,
            success: function (data) {
                if (typeof data.errors !== 'undefined') {
                    invalidHandler(data.errors);
                } else {
                    successHandler(data);
                }
            }
        }).done(function () {
            $(selectors.loader).hide();
        });
    };

    var bindEvents = function () {
        $(selectors.form).on('submit', function (e) {
            e.preventDefault();
            submitHandler();
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
    var search = Search();
    search.init();
});