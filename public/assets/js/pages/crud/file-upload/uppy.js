"use strict";

// Class definition
var KTUppy = (function () {
    const Tus = Uppy.Tus;
    const ProgressBar = Uppy.ProgressBar;
    const Informer = Uppy.Informer;

    var initUppy3 = function () {
        var id = "#kt_uppy_3";

        var uppyDrag = Uppy.Core({
            autoProceed: true,
            restrictions: {
                maxFileSize: 1000000, // 1mb
                maxNumberOfFiles: 5,
                minNumberOfFiles: 1,
                allowedFileTypes: ["image/*", "video/*"],
            },
        });

        uppyDrag.use(Uppy.DragDrop, { target: id + " .uppy-drag" });
        uppyDrag.use(ProgressBar, {
            target: id + " .uppy-progress",
            hideUploadButton: false,
            hideAfterFinish: false,
        });
        uppyDrag.use(Informer, { target: id + " .uppy-informer" });
        uppyDrag.use(Tus, { endpoint: "https://master.tus.io/files/" });

        uppyDrag.on("complete", function (file) {
            var imagePreview = "";
            $.each(file.successful, function (index, value) {
                var imageType = /image/;
                var thumbnail = "";
                if (imageType.test(value.type)) {
                    thumbnail = '<div class="uppy-thumbnail"><img src="' + value.uploadURL + '"/></div>';
                }
                var sizeLabel = "bytes";
                var filesize = value.size;
                if (filesize > 1024) {
                    filesize = filesize / 1024;
                    sizeLabel = "kb";
                    if (filesize > 1024) {
                        filesize = filesize / 1024;
                        sizeLabel = "MB";
                    }
                }
                imagePreview +=
                    '<input type="hidden" value="' + value.name +  '"name="image_cover[]" multiple/>' +
                    '<div class="row mt-5"> <div class="col-md-6"> <div class="uppy-thumbnail-container" data-id="' +
                    value.id +
                    '">' +
                    thumbnail +
                    ' <span class="uppy-thumbnail-label">' +
                    value.name +
                    " (" +
                    Math.round(filesize, 2) +
                    " " +
                    sizeLabel +
                    ')</span> <span data-id="' +
                    value.id +
                    '" class="uppy-remove-thumbnail"><i class="flaticon2-cancel-music"></i></span></div></div>' + 
                    '<div class="col-md-6">' + 
                    '<textarea name="detail_galeri[]" class="form-control" rows="3" placeholder="Masukkan keterangan gambar"></textarea>' + 
                    '</div></div>'
            });

            $(id + " .uppy-thumbnails").append(imagePreview);
        });

        $(document).on(
            "click",
            id + " .uppy-thumbnails .uppy-remove-thumbnail",
            function () {
                var imageId = $(this).attr("data-id");
                uppyDrag.removeFile(imageId);
                $(
                    id + ' .uppy-thumbnail-container[data-id="' + imageId + '"'
                ).remove();
            }
        );
    };

    return {
        // public functions
        init: function () {
            initUppy3();
        },
    };
})();

KTUtil.ready(function () {
    KTUppy.init();
});
