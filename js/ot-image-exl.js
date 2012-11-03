(function($){

    $(document).ready(function(){
        $('#option-tree-attachments-list li label, #option-tree-attachments-list li input').hide();
        $('#option-tree-attachments-list li ').live("click", function(){
            $attr = $(this).find('input').attr('checked');
            if($attr) {
                $(this).find('input').removeAttr('checked');
                $(this).removeClass('exclude');
            } else {
             $(this).find('input').attr('checked', true);
             $(this).addClass('exclude');
         }

     });


        $('#option-tree-attachments-list li input:checked').each(function (i){
            $(this).parent().addClass('exclude')
        });

        $('a.option-tree-attachments-update').click(function(e){
            e.preventDefault();
            var post_id = $("#post_ID").val();
            var field_id = $("#this_field_id").val();
            var field_name = $("#this_field_name").val();
            $('#option-tree-attachments-list').slideUp();

            $.ajax({
                type: 'POST',
                url: ajaxurl,
                dataType:'html',
                data: {
                    action: 'attachments_update',
                    post_id: post_id,
                    field_id: field_id,
                    field_name: field_name
                },
                success:function(res) {
                    $('#option-tree-attachments-list').html(res).slideDown();
                    $('#option-tree-attachments-list li label, #option-tree-attachments-list li input').hide();
                    $('#option-tree-attachments-list li input:checked').each(function (i){
                        $(this).parent().addClass('exclude')

                    });
                }
            });
        })

    });



})(this.jQuery);

