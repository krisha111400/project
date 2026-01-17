<?php
session_start();
session_unset();   // saare session variables clear kar dega
session_destroy(); // session khatam kar dega

// logout ke baad home page pe redirect
header("Location: ../Common Files/Home Page.php");
exit();
?>