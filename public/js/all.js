$(function () {
    "use strict";

    $(".Form__login").validate({
        email: {
            required: true,
            email: true
        },
        password: {
            required: true
        },
        errorElement: 'div',
        errorPlacement: function (error, element) {
            var placement = $(element).data('error');
            if (placement) {
                $(placement).append(error)
            } else {
                error.insertAfter(element);
            }
        }
    });
});
//# sourceMappingURL=all.js.map
