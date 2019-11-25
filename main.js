
// clicking anywhere on the document closes search results
$(document).click(function(e){
    if(e.target.class != "search_results" && e.target.id != "search_text_input"){
        $(".search_results").html("");
        $('.search_results_footer').html("");
        $('.results-wrapper').css("border", "")
        $('.search_results_footer').toggleClass("search_results_footer_empty");
        $('.search_results_footer').toggleClass("search_results_footer");
    }
})

//javascript code for dynamic searching
function getLiveSearchUsers(value){

    // ajax call
    $.post("includes/handlers/ajax_search.php", {query: value}, function(data){

       if($(".search_results_footer_empty")[0]){
            $(".search_results_footer_empty").toggleClass("search_results_footer");
            $(".search_results_footer_empty").toggleClass("search_results_footer_empty")
        }

        $('.search_results').html(data)
        $('.search_results_footer').html("<a href='search.php?q=" + value + "'>See All Results</a>")
        $('.results-wrapper').css("border", "solid 1px #e7e7e7")

        if(data === ""){
            $('.search_results_footer').html("");
            $('.search_results_footer').toggleClass("search_results_footer_empty");
            $('.search_results_footer').toggleClass("search_results_footer");
            $('.results-wrapper').css("border", "")
        }
    })
}

// jquery for getting comments dynamically
function getComments(post_id){
    //alert('getting comments from post: ' + post_id)
    // ajax call
    $.post("includes/handlers/ajax_get_comments.php", {query: post_id}, function(data){
        // display the data from the callback
        $('#comment-thread').html(data)
     })
}

// Opens edit profile and create post popups. 
function openEditProfileForm() {
    document.getElementById("edit-profile-form").style.display = "block";
}
function openCreatePostForm() {
    document.getElementById("create-post-form").style.display = "block";
}

// Closes the edit profile and create post popups. 
function closeForms() {
    document.getElementById("save-NewCaption").style.display = "none";
    document.getElementById("create-post-form").style.display = "none";
    document.getElementById("edit-profile-form").style.display = "none";
}




// Closes modifyCaptionForm
function closeCaptionForm() {
    document.getElementById("closeCaptionForms").style.display = "none";
}


$(document).ready(function(){
    $('.post-button').each(function(){
        var id = $(this).attr('id')
        $(this).click(function(index){
            document.getElementById("save-NewCaption").style.display = "block";
            $('#hidden-input').val(id)
        })
    });
})


// check if input file is valid
$(document).ready(function(){  
    $('#insert').click(function(){  
         var image_name = $('#image').val();  
         if(image_name == ''){  
              alert("Please Select Image");  
              return false;  
         }
         else {  
          var extension = $('#image').val().split('.').pop().toLowerCase();  4
             
          if(jQuery.inArray(extension, ['gif','png','jpg','jpeg']) == -1) {  
               alert('Invalid Image File');  
               $('#image').val('');  
               return false;  
            }  
         }  

    });  
});  


// inserting a new comment
$(document).ready(function(){

    $('#comment-form').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            type: 'post',
            url: 'includes/handlers/ajax_insert_comment.php',
            data: $('#comment-form').serialize(),
            success: function (post_id) {
                $('#comment-content').val(''); // clear input field
                getComments(post_id); // get new comments upon successfully inserting comment
            }
        });
    });
        
})