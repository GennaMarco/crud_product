// EVENTS
$('#sorter_up_down').click(function()
{
    $(this).children('i').toggleClass('fa-arrow-down fa-arrow-up');

    var criteria_class;

    switch($('#select_sorting')[0].value)
    {
        case 'Name':

            criteria_class = 'product-name-custom';
            var a_name;
            var b_name;
            PRODUCTS.sort(function (a, b)
            {
                a_name = $(a).find('.' + criteria_class).html();
                b_name = $(b).find('.' + criteria_class).html();

                return (ascending === a_name < b_name) ? 1 : -1;
            });

            break;


        case 'Price':

            criteria_class = 'product-price';
            var a_price;
            var b_price;
            PRODUCTS.sort(function (a, b)
            {
                a_price = parseFloat($(a).find('.' + criteria_class).html());
                b_price = parseFloat($(b).find('.' + criteria_class).html());

                return (ascending === a_price < b_price) ? 1 : -1;
            });

            break;
    }

    ascending = ascending ? false : true;

    $('#select_filter').val($('#select_filter')[0].value).trigger('change');
});

$('#select_sorting').change(function ()
{
    $('#sorter_up_down').trigger('click');
    $('#sorter_up_down').trigger('click');
});
// END EVENTS

// GLOBALS VARIABLES - Initialization
var ascending = false;
$('#sorter_up_down').trigger('click');
// END GLOBALS VARIABLES - Initialization


