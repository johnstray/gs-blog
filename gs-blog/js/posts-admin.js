/* Calculates and Set the Table's Minimum Hight value. */
function calcTableHeight(perPage) {
    var rowHeight = $("#datatable tr").height();
    var tableHeight = ((rowHeight + 2) * perPage) + 1;
    $("#tableContainer").css('min-height',tableHeight);
}

/* Hides or shows the table rows for the current page */
function sliceTableRows (pageNum, perPage) {
    var pageParts = $("#datatable tr");
    var start = perPage * (pageNum -1);
    var end = start + perPage;
    pageParts.hide().slice(start, end).show();
}

function deletePostAjax(item, perPage) {
    var message = item.attr("title"); /* Message to ask before deleting */
    var delhref = item.attr("href"); /* Link to follow to delete the post */
    var ptblrow = item.parents("tr"); /* Table row containing post */
    
    /* Set the row to italic so the user knows which post they are deleting. */
    ptblrow.css("font-style", "italic");
    
    if ( confirm(message) ) {
        if ( !item.hasClass("noajax") ) {
            $("#loader").show(); /* Show the Loading Spinner (next to tabs) */
            ptblrow.addClass("deletedrow"); /* Add deletedrow class to table row tr */
            $.ajax({
                type: "GET", url: delhref, dataType: "html", /* Ajax settings */
                success: function (response) {
                    if ( $(response).find("div.blogMsgError").html() ) {
                        /* There was on error message in the response - Notify user */
                        notifyError( $(response).find("div.blogMsgError").html() );
                        return;
                    } else if ( $(response).find("div.blogMsgOk").html() ) {
                        notifyOk( $(response).find("div.blogMsgOk").html() );
                        ptblrow.fadeOut(500); ptblrow.remove();
                        sliceTableRows($("#pageNavPosition").pagination('getCurrentPage'), perPage);
                        $("#pageNavPosition").pagination('updateItems', $("#datatable tr").length);
                   } else {
                        notifyWarn( "Couldn't process response of request - Please refresh page to see if your post has actually been deleted.");
                   }
                },
                error: function () {
                    notifyError("An error occurred sending the request and the post probably hasn't been deleted. Please refresh the page, check, then try again.");
                }
            });
            $("#loader").fadeOut(500);
        } else {
            /* Manually follow the link */
        }
    } else {
        ptblrow.css("font-style", "normal");
        return false;
    }
}
