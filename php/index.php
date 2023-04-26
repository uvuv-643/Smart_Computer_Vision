<?php

$image = $_POST['file'];
$imagename = $_FILES['file']['name'];
$imagetype = $_FILES['file']['type'];
$imageerror = $_FILES['file']['error'];
$imagetemp = $_FILES['file']['tmp_name'];

$imagePath = "/var/www/images/";
if (!is_dir($imagePath)) {
    mkdir($imagePath);
}

if (is_uploaded_file($imagetemp)) {
    if (move_uploaded_file($imagetemp, $imagePath . $imagename)) {
        echo "Successfully uploaded your image.";
    } else {
        echo "Failed to move your image.";
    }
} else {
    echo "Failed to upload your image.";
}

