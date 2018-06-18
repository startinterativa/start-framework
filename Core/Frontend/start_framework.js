$('.upload').on('change', function() {
    var form_data = new FormData();
    
    var ins = $(this).prop('files').length;
    for (var x = 0; x < ins; x++) {
        form_data.append("file[]", $(this).prop('files')[x]);
    }
    
    form_data.append('folder', $(this).data('folder'));
    $('#preloader').show();
    $.ajax({
        url: "Scripts/upload.php",
        type: "POST",
        processData: false,
        contentType: false,
        data: form_data,
        success: function(result){
            if(result.success) {
                $('.galeria').slick('removeSlide', null, null, true);
                var pathValue = [];
                $.each(result.files,function(index, value){
                    if(result.type == 'galeria') {
                        $('.galeria').slick('slickAdd','<div class="slide"><img class="img-responsive" src="'+value+'"/></div>');
                    }
                    pathValue.push(value);
                });
                $("#upload_path").prop("value", pathValue.join());
                $("#upload_img").prop("src", result.files[0]);
                $('#preloader').hide();
                console.log(pathValue.join());
            }
        }
    });
});