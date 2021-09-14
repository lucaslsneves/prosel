<?php
 $mysqli = new mysqli("", "", "", "");
 $result = $mysqli->query("SELECT DATABASE()");
 $row = $result->fetch_row();
 $mysqli->select_db("");
