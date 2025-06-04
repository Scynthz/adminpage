<?php
session_start();
session_unset();
session_destroy();
header("Location: index.php"); // redirect to your home page
exit();
?>
