<?php 
$fullPath="";
function realTime($cmd)
{
    // Open a process for the command

    while (@ob_end_flush());
        $process = popen($cmd . ' 2>&1', 'r');
        // Read the output of the process line by line
    while (!feof($process)) {
        while ($line = fgets($process)) {
            // Parse the output to extract the progress information
            // (e.g. "frame= 100 fps= 25 q=28.00 size=    1250kB time=00:00:04.00 bitrate=2719.2kbits/s")
            $matches = array();
            if (preg_match('/time=(\d+:\d+:\d+.\d+)/', $line, $matches)) {
                // Extract the time from the match
                $time = $matches[1];
                // Do something with the time, such as displaying it to the user
                echo "Progress: $time\n";
                @flush();
                // ob_flush();
                sleep(1);
            }
        }
    }
        // Close the process
        pclose($process);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="Description" content="Enter your description here"/>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<link rel="stylesheet" href="assets/css/style.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<title>Video Compressor</title>
</head>
<body>
    <div class="container" style="margin-top: 200px;">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <h1>Video compressor</h1>
     
                <form id="compressor" method="POST" enctype="multipart/form-data" action="">
                    <div class="form-group">
                        <label>Select video</label>
                        <input type="file" name="video" class="form-control" enctype="multipart/form-data" required="" accept="video/*">
                    </div>

                    <div class="form-group">
                        <label>Select name for the compressed video</label>
                        <input type="text" name="newName" class="form-control">
                    </div>
     
                    <div class="form-group">
                        <label>Select bitrate</label>
                        <select name="bitrate" class="form-control">
                            <option value="350k">240p</option>
                            <option value="700k">360p</option>
                            <option value="1200k">480p</option>
                            <option value="2500k">720p</option>
                            <option value="5000k">1080p</option>
                        </select>
                    </div>
                    <input type="submit" name="change_bitrate" class="btn btn-info" value="Compress the video">
                </form>
                <?php 
                if(isset($_POST['change_bitrate'])) {  
                    $video = $_FILES["video"]["tmp_name"];
                    $bitrate = $_POST["bitrate"];
                    $newName = $_POST["newName"];
                    $pathName = 'C:\your_path\\';
                    $ffmpegPath = "C:\\path_of_ffmpeg.exe";
                    $mp4 = '.mp4';
                    $fullPath = $pathName.$newName.$mp4;
                    $command = "$ffmpegPath -i $video -b:v $bitrate -bufsize $bitrate $fullPath";
                    echo "<pre>";
                    realTime($command);
                    echo "</pre>";
                }
                ?>

                <p id="commandCmd"><?php echo ($fullPath == '') ? "" : "File created at ".$fullPath; ?></p>
            </div>
        </div>
    </div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/js/bootstrap.min.js"></script>
</body>
</html>