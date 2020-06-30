$(document).ready(function(){
    $("#image-upload").change(function() {
        var name = $(this).prop('name');
        readURL(this, name);
    });
    
    function readURL(input, name) {
        if (input.files && input.files[0]) {
            $('#image-preview').css('background-image', 'none');

            var reader = new FileReader();
        
            reader.onload = function(e) {
                $('#image-preview').css('background-image', 'url(' + e.target.result + ')');
                $('#image-preview').removeClass('hide-preview');
            }
        
            reader.readAsDataURL(input.files[0]);
        }
    }    
});