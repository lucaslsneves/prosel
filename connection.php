<?php
 $mysqli = new mysqli("localhost", "root", "3451", "prosel");
 $result = $mysqli->query("SELECT DATABASE()");
 $row = $result->fetch_row();
 $mysqli->select_db("prosel");
