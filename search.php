<?php
session_start();
/*
Search in YouTube using Api . v3
*/

require_once "YouTube.php";
$Video = new YouTube();
$query = $_POST['q'] ?? '';
try {
    $_SESSION['result'] = $Video->sortByView($Video->search($query));
} catch (Exception $e) {
}
$_SESSION['q'] = $query;

header("Location: index.php"); die;