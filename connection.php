<?php
 $mysqli = new mysqli("localhost", "root", "", "prosel2");
 $result = $mysqli->query("SELECT DATABASE()");
 $row = $result->fetch_row();
 $mysqli->select_db("prosel2");
