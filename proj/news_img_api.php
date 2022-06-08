<?php
header('Content-Type: application/json');

$folder = __DIR__ . '/uploaded/';

move_uploaded_file($_FILES['img']['tmp_name'], $folder . $_FILES['img']['name']);

echo json_encode($_FILES);
