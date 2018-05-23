// EVENTS
$('#select_filter').change(function()
{
    var numProductsPerPage = parseInt(this.value);
    var numCurrentPage = 1;
    var lastSelectedPage = 1;

    InitNumPages(numProductsPerPage, TOTAL_NUM_PRODUCTS);
    HandleCarets(numCurrentPage, 'caret_left', 'caret_right', totalNumPages);
    MakePagination(numProductsPerPage, numCurrentPage, TOTAL_NUM_PRODUCTS);

    var currentClickedElement = $('.store-pages li a')[numCurrentPage];
    $(currentClickedElement).parent().addClass('customActive_li');

    $('.store-pages li a').click(function()
    {
        numCurrentPage = this.innerHTML;

        if (!isNormalInteger(numCurrentPage))
        {
            var classCaret = $(this).children('.fa')[0].className;

            if(classCaret.indexOf('right') >= 0)
            {
                numCurrentPage = Math.floor(lastSelectedPage) + 1;
            }
            else
            {
                numCurrentPage = Math.floor(lastSelectedPage) - 1;
            }
        }

        var currentClickedElement = $('.store-pages li a')[numCurrentPage];
        var lastClickedElement = $('.store-pages li a')[lastSelectedPage];

        $(currentClickedElement).parent().addClass('customActive_li');
        $(lastClickedElement).parent().removeClass('customActive_li');

        HandleCarets(numCurrentPage, 'caret_left', 'caret_right', totalNumPages);
        MakePagination(numProductsPerPage, numCurrentPage, TOTAL_NUM_PRODUCTS);

        lastSelectedPage = numCurrentPage;
    });
});
// END EVENTS


// GLOBALS VARIABLES - Initialization
var PRODUCTS = $( '.product_single_to_innerHTML' );
const TOTAL_NUM_PRODUCTS = PRODUCTS.length;
var totalNumPages;
$('#select_filter').val($('#select_filter')[0].value).trigger('change');
// END GLOBALS VARIABLES - Initialization


//FUNCTIONS
function InitNumPages(records_per_page, total_records)
{
    totalNumPages = total_records/records_per_page;

    var listPageFilter = $('#list_page_filter');
    listPageFilter.html(' <li><span class="text-uppercase">Page:</span></li> ');
    listPageFilter.append(' <li id="caret_left"><a href="#"><i class="fa fa-caret-left"></i></a></li> ');

    for(var i = 0; i<totalNumPages; i++)
    {
        listPageFilter.append(' <li><a href="#">' + (i+1) + '</a></li> ');
    }

    listPageFilter.append(' <li id="caret_right"><a href="#"><i class="fa fa-caret-right"></i></a></li> ');
}

function MakePagination(records_per_page, num_current_page, total_records)
{
    var listingProducts = $('#listing_products');
    listingProducts.html('');

    for (var i = (num_current_page-1) * records_per_page; i < (num_current_page * records_per_page) && i < total_records; i++)
    {
        listingProducts.append(PRODUCTS[i].innerHTML);
    }
}

function HandleCarets(num_current_page, id_caret_left, id_caret_right, total_num_pages)
{
    if (num_current_page < 2)
    {
        $('#' + id_caret_left ).hide();
    }
    else
    {
        $('#' + id_caret_left).show();
    }

    if (num_current_page >= total_num_pages)
    {
        $('#' + id_caret_right).hide();
    }
    else
    {
        $('#' + id_caret_right).show();
    }
}

function isNormalInteger(str)
{
    return /^\+?(0|[1-9]\d*)$/.test(str);
}
// END FUNCTIONS
