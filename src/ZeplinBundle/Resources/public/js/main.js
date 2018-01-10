$(document).ready(function () {
    var $modal = $('#photo-modal');
    $('.click_to_load_modal_popup').on('click', function () {
        $modal.load('/admin/edit/' + $(this).attr("data-id"),
            function () {
                $modal.modal('show');
            });
    });

    $(document).on('click', '.buttons-container button.edit', function () {
        var imgId = $("input[name='post[image]']").val();

        var data = {
                post : {
                    title: $("input[name='post[title]']").val(),
                    description: $("textarea[name='post[description]']").val(),
                    _token: $("input[name='post[_token]']").val(),
                    image: imgId,
                }
        };
        $.post("/admin/edit/" + imgId, data, function (data, status) {
            $modal.html(data);
        });
    });


    $(document).on('click', '.buttons-container button.delete', function () {

        var data = {
            post : {
                _token: $("input[name='post[_token]']").val(),
                image: $("input[name='post[image]']").val(),
                title: "default",
                description: "default"
            }
        };
        $.post("/admin/delete/", data, function (data, status) {
            $modal.html(data);
        });
    });

    $('.set-default-picture').on('click', function () {

        $modal.load('/admin/update-profile-pic/' + $(this).attr("data-id"),
            function () {
                $modal.modal('show');
        });
    })


});




