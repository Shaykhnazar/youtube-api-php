<?php
session_start();
/*
Search in YouTube using Api . v3
*/

require_once "YouTube.php";
$Video = new YouTube();
$query = $_POST['q'] ?? '';
$_SESSION['result'] = $Video->search($query);
$_SESSION['q'] = $query;

header("Location: index.php"); die;