$('.new-question').on('click', function(event) {
    event.preventDefault();   
    link = $(this).attr('href');
    text = $(this).html();
    $(document).find('.panel').slideUp(350, function() {
        $(this).remove();
        window.location.replace(link);
    });
});