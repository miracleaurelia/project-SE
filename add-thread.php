<?php
require_once('header.php');
if(!isset($_SESSION['customer'])) {
    header('location: logout.php');
    exit;
}

if (isset($_SESSION['customer'])) {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $database = "ecommerceweb";
    $connection = mysqli_connect($host, $user, $pass, $database);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/add-thread.css">
    <title>Add Thread</title>
    <script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
    <script src="./ckfinder/ckfinder.js"></script>
</head>

<body>
    <div class="page-banner">
        <div class="inner">
            <h3 style="color: black;">Add Post</h3>
        </div>
    </div>
    <form action="" method="POST" id="thread-form">
        <div align="center">Select Post Category</div>

        <div class="sel-category">
            <div class="select">
                <select name="sel-cat" id="sel-cat">
                    <option value="">- Select -</option>
                    <?php
                    $tcat = "SELECT * FROM tbl_top_category";
                    $get_tcat = mysqli_query($connection, $tcat);
                    while ($row = mysqli_fetch_assoc($get_tcat)) {
                        $tcat_id = $row['tcat_id'];
                        $tcat_name = $row['tcat_name'];
                        echo "<option value='" . $tcat_id . "' >" . $tcat_name . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="select">
                <select name="sel-mcat" id="sel-mcat">
                    <option value="">- Select -</option>
                </select>
            </div>
        </div>

        <div class="container post-info">
            <div class="form-group">
                <label for="post-title">Post Title</label>
                <input type="text" class="form-control" id="post-title" name="post-title" placeholder="Post Title">
            </div>

            <label for="editor" style="margin: 0">Post Content</label>
            <textarea id="editor" name="editor"></textarea><br>

            <div class="submit-button">
                <button type="button" class="btn btn-info" id="addButton">Add Post</button>
            </div>
        </div>
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'), {
                mediaEmbed: {
                    previewsInData: true
                },
                ckfinder: {
                    uploadUrl: './ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
                }
            })
            .then(newEditor => {
                editor = newEditor;
            })
            .catch(error => {
                if (error) {
                    console.error(error);
                }
            });

        $(document).ready(function() {
            $("#sel-cat").change(function() {
                var tcatid = $(this).val();
                $.ajax({
                    url: 'getcategory.php',
                    type: 'post',
                    data: {
                        "tcatid": tcatid
                    },
                    dataType: 'json',
                    success: function(response) {
                        var len = response.length;
                        $("#sel-mcat").empty();
                        for (var i = 0; i < len; i++) {
                            var id = response[i]['id'];
                            var name = response[i]['name'];
                            $("#sel-mcat").append("<option value='" + id + "'>" + name + "</option>");
                        }
                    }
                });
            });

            $("#addButton").click(function(e) {
                let valid = 1;
                var posttcat = $('#sel-cat').val();
                var postmcat = $('#sel-mcat').val();
                var posttitle = $('#post-title').val();
                var postContent = editor.getData();
                if (posttcat == '') {
                    valid = 0;
                }
                if (postmcat == '') {
                    valid = 0;
                }
                if (posttitle == '') {
                    valid = 0;
                }
                if (postContent == '') {
                    valid = 0;
                }
                if (!valid) {
                    alert("Be sure to fill everything!");
                } else {
                    $.ajax({
                        type: "POST",
                        url: "./add-thread-to-db.php",
                        data: {
                            "posttcat": posttcat,
                            "postmcat": postmcat,
                            "posttitle": posttitle,
                            "postContent": postContent
                        },
                        success: function(data) {
                            alert("Post successfully created!");
                            window.location = "./post.php?title=" + data;
                        }
                    });
                }
            });

        });
    </script>
</body>

</html>

<?php require_once('footer.php'); ?>