<?php
session_start();
$env = parse_ini_file('../.env');
if(isset($_POST['password']) && $_POST['password'] == $env['ARCADE_PASSWORD']) {
    $_SESSION['ArcadeAuthenticated'] = true;
    error_log('New Authorized User Accessed the Arcade.');
    if(!isset($_GET['cloudSaves'])) {
        header('Location: ?cloudSaves=1');
        exit();
    }
    readfile('../index.html');
    exit();
}

if(isset($_GET['logout'])) {
    unset($_SESSION['ArcadeAuthenticated']);
}

$path = urldecode(trim(explode('?', $_SERVER['REQUEST_URI'])[0], DIRECTORY_SEPARATOR));
if (strstr($path, 'manifest.json') || strstr($path, 'apple-touch-icon.png')) {
    header('Content-Type: ' . mime_content_type('../' . $path));
    readfile('../' . $path);
    exit();
}

if(isset($_SESSION['ArcadeAuthenticated'])) {

    if (!file_exists('../roms/map.json')) {
        function ReadFolderDirectory($dir, $listDir = array())
        {
            if($handler = opendir($dir)) {
                while (($sub = readdir($handler)) !== false) {
                    if (!in_array($sub, ['.', '..' , 'Thumb.db', '__MAC_OSX', '.DS_Store', '@eaDir'])) {
                        if (is_file($dir . DIRECTORY_SEPARATOR . $sub)) {
                            $ext = pathinfo($sub, PATHINFO_EXTENSION);
                            if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif'])) {
                                continue;
                            }
                            $listDir[] = [ 'type' => 'file', 'name' => str_replace('../roms', '.', $dir) . DIRECTORY_SEPARATOR . $sub];
                        } elseif(is_dir($dir . DIRECTORY_SEPARATOR . $sub)) {
                            $listDir[] = [ 'type' => 'directory', 'name' => str_replace('../roms', '.', $dir) . DIRECTORY_SEPARATOR . $sub, 'contents' => ReadFolderDirectory($dir . DIRECTORY_SEPARATOR . $sub)];
                        }
                    }
                }
                closedir($handler);
            }
            return $listDir;
        }
        file_put_contents('../roms/map.json', json_encode([[ 'type' => 'directory', 'name' => './', 'contents' => ReadFolderDirectory('../roms')]], JSON_PRETTY_PRINT + JSON_UNESCAPED_SLASHES));
    }

    if (isset($_GET['save'])) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_FILES['state']) && isset($_FILES['screenshot']) && isset($_POST['gameName'])) {
                $gameName = $_POST['gameName'];
                $stateFile = $_FILES['state']['tmp_name'];
                $screenshotFile = $_FILES['screenshot']['tmp_name'];
                $stateData = '';
                $stateHandle = fopen($stateFile, 'rb');
                while (!feof($stateHandle)) {
                    $stateData .= fread($stateHandle, 8192);
                }
                fclose($stateHandle);
                $screenshotData = '';
                $screenshotHandle = fopen($screenshotFile, 'rb');
                while (!feof($screenshotHandle)) {
                    $screenshotData .= fread($screenshotHandle, 8192);
                }
                fclose($screenshotHandle);
                file_put_contents("../saves/{$gameName}.state", $stateData, LOCK_EX);
                file_put_contents("../screenshots/{$gameName}.png", $screenshotData, LOCK_EX);

                echo 'Data saved successfully!';
            } else {
                echo 'Invalid data received.';
            }
        }
        exit();
    }

    $path = urldecode(trim(explode('?', $_SERVER['REQUEST_URI'])[0], DIRECTORY_SEPARATOR));
    header('Cross-Origin-Opener-Policy: same-origin');
    header('Cross-Origin-Embedder-Policy: require-corp');
    if(empty($path)) {
        readfile('../index.html');
        exit();
    }

    if(strstr($path, 'play.html') && !isset($_GET['cloudSaves'])) {
        header('Location: ?' . (isset($_GET['game']) ? ('game=' . $_GET['game'] . '&') : '') . 'cloudSaves=1');
        exit();
    }

    $mime = mime_content_type('../' . $path);
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    $ext2mimes = [
      'css' => 'text/css',
    ];

    if (array_key_exists($ext, $ext2mimes)) {
        $mime = $ext2mimes[$ext];
    }

    header('Content-Type: ' . $mime);
    readfile('../' . $path);
    exit();
}

if(!isset($_SESSION['ArcadeAuthenticated']) || $_SESSION['ArcadeAuthenticated'] !== true) {
    http_response_code(403);
}

?>
<html>
  <head>
    <meta name = viewport content = "width = device-width, initial-scale = 1">
    <link rel="icon" type="image/png" href="apple-touch-icon.png">
    <link rel="apple-touch-icon" href="apple-touch-icon.png">
    <link rel="manifest" href="manifest.json">
    <link rel="shortcut icon" href="favicon.ico">
    <meta name="apple-mobile-web-app-title" content="WEmu">
    <meta name="application-name" content="WEmu">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="theme-color" content="#000000">
    <style>
      body {
        font-family: sans-serif;
        text-align: center;
        background-color: black;
        color: white;
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
  <?php if($_SERVER['REQUEST_METHOD'] == 'POST') {
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