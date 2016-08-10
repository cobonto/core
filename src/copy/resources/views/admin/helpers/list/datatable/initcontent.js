function(){
    $('.destroy').on('click',function(e){
        e.preventDefault();
        var element = $(this);
        $.confirm({
            title: 'Are you sure want to delete ?',
            content: false,
            theme: 'white',
            confirm: function(){
               var href= element.attr('href');
                $('body').append('<form id="delete_row" action="'+href+'" method="POST">'+
                    '<input type="hidden" name="_method" value="DELETE">'+
                    '<input type="hidden" name="_token" value="csrf_token"></form>');
                $('#delete_row').submit();
            }
        });
    });
    }