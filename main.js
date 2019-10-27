
// include this code when then document renders
$(document).ready(function(){

    // animate search bar on click (only for large screens)
    $('#search_text_input').focus(function(){
        if(window.matchMedia( "(min-width: 800px)").matches){
            $(this).animate({width : '250px'}, 200)
        }
    })

})

// clicking document closes search results
$(document).click(function(e){
    if(e.target.class != "search_results" && e.target.id != "search_text_input"){
        $(".search_results").html("");
        $('.search_results_footer').html("");
        $('.search_results_footer').toggleClass("search_results_footer_empty");
        $('.search_results_footer').toggleClass("search_results_footer");
    }
})



//javascript code for dynamic searching
function getLiveSearchUsers(value, user){

    // ajax call
    $.post("includes/handlers/ajax_search.php", {query: value}, function(data){

        if($(".search_results_footer_empty")[0]){
            $(".search_results_footer_empty").toggleClass("search_results_footer");
            $(".search_results_footer_empty").toggleClass("search_results_footer_empty")
        }

        $('.search_results').html(data)
        $('.search_results_footer').html("<a href='search.php?q=" + value + ">See All Results</a>")

        if(data = ""){
            $('.search_results_footer').html("");
            $('.search_results_footer').toggleClass("search_results_footer_empty");
            $('.search_results_footer').toggleClass("search_results_footer");
        }
    })
}

