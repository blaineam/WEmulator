<?php
 session_start();
 $env = parse_ini_file('../.env');
 if( isset($_POST['password']) && $_POST['password'] == $env['ARCADE_PASSWORD'] ) {
   $_SESSION['ArcadeAuthenticated'] = true;
   error_log('New Authorized User Accessed the Arcade.');
    if(!isset($_GET['cloudSaves'])) {
      header("Location: ?cloudSaves=1");
      exit();
    }
   readfile("../index.html");
   exit();
 }


 if(isset($_GET['logout'])) {
  unset($_SESSION['ArcadeAuthenticated']);
 }

 if(isset($_SESSION['ArcadeAuthenticated'])) {
    if (isset($_GET['save'])) {
      if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if (isset($_FILES["state"]) && isset($_FILES["screenshot"]) && isset($_POST["gameName"])) {
            $gameName = $_POST["gameName"];
            $stateFile = $_FILES["state"]["tmp_name"];
            $screenshotFile = $_FILES["screenshot"]["tmp_name"];
            $stateData = "";
            $stateHandle = fopen($stateFile, "rb");
            while (!feof($stateHandle)) {
                $stateData .= fread($stateHandle, 8192);
            }
            fclose($stateHandle);
            $screenshotData = "";
            $screenshotHandle = fopen($screenshotFile, "rb");
            while (!feof($screenshotHandle)) {
                $screenshotData .= fread($screenshotHandle, 8192);
            }
            fclose($screenshotHandle);
            file_put_contents("../saves/{$gameName}.state", $stateData, LOCK_EX);
            file_put_contents("../screenshots/{$gameName}.png", $screenshotData, LOCK_EX);
    
            echo "Data saved successfully!";
        } else {
            echo "Invalid data received.";
        }
      }
      exit();
    }

    $path = urldecode(trim(explode('?', $_SERVER['REQUEST_URI'])[0], DIRECTORY_SEPARATOR)); 
    header("Cross-Origin-Opener-Policy: same-origin");
    header("Cross-Origin-Embedder-Policy: require-corp");
    if(empty($path)) {
      if(!isset($_GET['cloudSaves'])) {
        header("Location: ?cloudSaves=1");
        exit();
      }
      readfile("../index.html");
    }
    $mime = mime_content_type("../" . $path);
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    $ext2mimes = [
      "css" => "text/css",
    ];
    
    if (array_key_exists($ext, $ext2mimes)) {
      $mime = $ext2mimes[$ext];
    }

    header("Content-Type: ".$mime);
    readfile("../" . $path);
    exit();
 }

 if( !isset($_SESSION['ArcadeAuthenticated']) || $_SESSION['ArcadeAuthenticated'] !== true) {
    http_response_code(403);
 }

?>
<html>
  <head>
    <style>
      body {
        font-family: sans-serif;
        text-align: center;
      }
      form {
          width: fit-content;
          margin: 0 auto;
      }

      input {
          display: inline-block;
          width: 300px;
          max-width: calc(50% - 35px);
          margin: 14px;
          appearance: none;
          /*  safari  */
          -webkit-appearance: none;
          font-size: 16px;
          padding: 7px;
          background-color: #333333EE;
          border: 0px solid transparent;
          border-radius: 0.25rem;
          color: white;
          cursor: pointer;
      }
    </style>
<body>
  <?php if( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    error_log('Invalid Access attempt received');
  ?>
    Invalid password
  <?php } ?>
<form method="POST">
  <input type="password" name="password" placeholder="password">
  <input type="submit" value="Login">
</form>

</body>
</html>