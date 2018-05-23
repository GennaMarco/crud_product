$('#user_imagePath').change(function()
{
    var i = $(this).prev('label').clone();
    var file = $('#user_imagePath')[0].files[0].name;
    $('#name-of-file-uploaded').text(file);
});
