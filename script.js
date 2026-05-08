$(document).ready(function () {

    loadBooks();

});



function loadBooks()
{
    $.ajax({

        url: "handler.php",

        type: "POST",

        data: {
            action: "fetch"
        },

        success: function (data) {

            $("#bookData").html(data);
        }
    });
}




function addBook()
{
    let title = $("#title").val();

    let author = $("#author").val();

    let category = $("#category").val();

    let status = $("#status").val();


    $.ajax({

        url: "handler.php",

        type: "POST",

        data: {

            action: "add",

            title: title,
            author: author,
            category: category,
            status: status
        },

        success: function () {

            loadBooks();

            $("#title").val('');
            $("#author").val('');
            $("#category").val('');
        }
    });
}




function deleteBook(id)
{
    $.ajax({

        url: "handler.php",

        type: "POST",

        data: {

            action: "delete",

            id: id
        },

        success: function () {

            loadBooks();
        }
    });
}