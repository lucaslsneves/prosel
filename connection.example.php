<?php
 $mysqli = new mysqli("host", "user", "password", "db");
 $result = $mysqli->query("SELECT DATABASE()");
 $row = $result->fetch_row();
 $mysqli->select_db("prosel");