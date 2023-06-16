<?php

    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'pradeep';
    $DATABASE_PASS = '12345678';
    $DATABASE_NAME = 'kaustubha';
    // $DATABASE_NAME_DOCTORS = 'form2';

    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    // $con_doctors = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME_DOCTORS);

    if (mysqli_connect_error()) {
        exit('Error connecting to the database: ' . mysqli_connect_error());
    }

?>