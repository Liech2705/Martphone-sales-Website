
$(document).ready(function() {
    $('#content').summernote({
        height: 300,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['fontsize', 'color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['insert', ['videoUpload', 'link', 'picture']]
        ],
        callbacks: {
            onImageUpload: function(files) {
                for (let i = 0; i < files.length; i++) {
                    uploadImage(files[i]);
                }
            }
        }
    });

    function uploadImage(file) {
        let data = new FormData();
        data.append("image", file);

        $.ajax({
            url: 'upload_image.php', // đường dẫn xử lý upload ảnh
            method: 'POST',
            data: data,
            contentType: false,
            cache: false,
            processData: false,
            success: function(url) {
                $('#content').summernote('insertImage', url);
            },
            error: function() {
                alert("Không thể upload ảnh.");
            }
        });
    }
});

$.extend($.summernote.options.buttons, {
videoUpload: function (context) {
    let ui = $.summernote.ui;
    let button = ui.button({
        contents: '<i class="note-icon-video"/> Upload Video',
        tooltip: 'Upload Video từ máy',
        click: function () {
            let fileInput = $('<input type="file" accept="video/*">');
            fileInput.trigger('click');
            fileInput.on('change', function () {
                let file = this.files[0];
                let formData = new FormData();
                formData.append('video', file);

                $.ajax({
                    url: 'upload_video.php',
                    method: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (url) {
                        const videoTag = `
                            <div class="video-wrapper" contenteditable="false" style="display:inline-block; resize: both; overflow: auto; max-width: 100%; border: 1px dashed #ccc; padding: 5px;">
                                <video controls style="width: 100%; height: auto;">
                                    <source src="${url}" type="video/mp4">
                                </video>
                            </div><p><br></p>`;
                            $('#content').summernote('pasteHTML', videoTag);
                    },
                    error: function () {
                        alert("Không thể upload video.");
                    }
                });
            });
        }
    });
    return button.render();
}
});
 