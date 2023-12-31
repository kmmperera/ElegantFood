// console.log(ajax_object.ajax_url);

jQuery(document).ready(function($){
    $('.food-add-btn').click(function(e){
        e.preventDefault();
        console.log('Form Submitted');

        let formSelected = e.currentTarget.parentElement;
        let product_id   = document.getElementById(formSelected.id + '-hidden').value;

        let values = [];

        values = Array.from( document.querySelectorAll( 'input[type=checkbox]:checked' )).map(item=>item.value);
        
        
            $.ajax({
                // Pass the admin-ajax.php into url.
                url: ajax_object.ajax_url,
                data: {
                    'action': 'eleganttheme_food_ajax_add_to_cart',
                    'product_id': product_id,
                    'variations': values
                },
                type: 'post',
                success: function(res){
                    console.log(res);
                    window.location.reload();
                },
                error: function(err){
                    console.log(err);
                },
            });
        

        formSelected.reset();
        
        // console.log(formSelected);
        // console.log(values);
        
    });
});