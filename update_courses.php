<?php
require("includes/functions.php");
 $data = @file_get_contents("https://api.cs50.net/courses/1.0/courses?output=json");
 $courses=json_decode($data,true);
  
    $rows=query("CREATE TABLE allharvardcourses
    (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    department VARCHAR(255),
    cat_num VARCHAR(16),
    term VARCHAR(16),
    number VARCHAR(10)
    )");
 foreach ($courses as $course){
    print_r($course);
    query("INSERT INTO allharvardcourses (name, department, number, cat_num, term) VALUES(?,?,?,?,?)",
    $course["title"], $course["field"], $course["number"], $course["cat_num"], $course["term"]);
 }
 
?>
