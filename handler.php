<?php

include("functions.php");

$action = $_POST['action'];



if ($action == "add") {

    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $status = $_POST['status'];

    addBook($title, $author, $category, $status);

    echo "Book Added";
}



if ($action == "fetch") {

    $result = getBooks();

    while ($row = mysqli_fetch_assoc($result)) {

        echo "<tr>

                <td>{$row['id']}</td>
                <td>{$row['title']}</td>
                <td>{$row['author']}</td>
                <td>{$row['category']}</td>
                <td>{$row['status']}</td>

                <td>

                    <button onclick='deleteBook({$row['id']})'>
                        Delete
                    </button>

                </td>

              </tr>";
    }
}



if ($action == "delete") {

    $id = $_POST['id'];

    deleteBook($id);

    echo "Deleted";
}


if ($action == "update") {

    $id = $_POST['id'];

    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];
    $status = $_POST['status'];

    updateBook($id, $title, $author, $category, $status);

    echo "Updated";
}

?>