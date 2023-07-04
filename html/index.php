<?
function have_filesize($bytes) {
    $size = $bytes / 1024;
    if($size < 1024) {
        $size = number_format($size, 2);
        $size .= ' KB';
     } else {
        if($size / 1024 < 1024) {
            $size = number_format($size / 1024, 2);
            $size .= ' MB';
        } else if ($size / 1024 / 1024 < 1024) {
            $size = number_format($size / 1024 / 1024, 2);
            $size .= ' GB';
        } 
     }
    return $size; 
}
// Set the file's URL to a variable for easy reference
if(strstr($_SERVER['SCRIPT_FILENAME'], "/index.php") !== FALSE) {
    $self = $_SERVER['REQUEST_URI'];
    $directory = explode("/", $self);
    $counter = count($directory);
    $your_dir = "for /".$directory[$counter-2];
    if($_POST) {

        $target_path = $directory[$counter-2].'/';

        $target_path = basename( $_FILES['uploadedfile']['name']); 

        if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
            echo "The file ".  basename( $_FILES['uploadedfile']['name']). 
            " has been uploaded";
        } else{
            echo "There was an error uploading the file, please try again!";
        }

        }
}
?>

<?php
$err = "";
if (!empty($_POST)) {
  $user = $_POST['suggestion'];
  file_put_contents('suggestions.txt',"$user\n",FILE_APPEND);
  $err = "Thanks for your suggestion!";
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Free Netflicks <?=$your_dir;?></title>
<style type="text/css">
body { font-family: 'Lucida Sans', Verdana, Arial, Sans-Serif; color: #333; font-size: 76%; }
ul { list-style-type: none; padding: 10px; margin: 0; width: 600px; }
li span { float: right; }
li { border-collapse: collapse; vertical-align: bottom; border-bottom: 1px solid #eee; padding: 8px 10px 3px 5px; font-size:.95em; }
li.alt { border-collapse: collapse; vertical-align: bottom; border-bottom: 1px solid #eee; background-color: #fcfcfc; }
li:hover { background-color: #f4f4f4; }
a { color: #333; text-decoration: none; outline:none; }
a:hover { color: #f0a; }
img { border: none; margin: 0 3px -3px 0; }
h3 { color: #3a3; font-family: 'Lucida Sans', Verdana, Arial, Sans-Serif; font-size: 1.8em; font-weight: 100; margin-bottom: 0; }
</style>
</head>
<body>
<img src="freenetflix.svg" alt="" title="Free Netflix" width="75%" />


<p>Free Netflicks allows free access to files limited to the range of the wireless network.  We expect you can browse our selection quickly and download the file to your device.  Save and watch it later!</p>




<!--/*
Use this for forcing downloads of links:
<a href="/localfile.ext" download="SavedAsName.ext">Download SavedAsName</a>
*/-->




<?
// Set the background color for table cells to alternate between
$cellcolor2="alt";

?><h1>Directory Listing <?=$your_dir;?></h1><?
// Read the files from the directory
$Dir = ".";
$Open = opendir ($Dir);
while ($Files = readdir ($Open)) {
    $Filename = "$Dir/" . $Files;
    $Type = filetype ("$Filename");
    if ($Files == '..'|| $Files == '.' || $Files == 'index.php') {
        continue;
    } else {
        //If the file is a directory, list it out with a folder icon
        if (is_dir ($Filename)) {
            $Name = "<a href=\"".$Filename."\"><img src=\"/i/folder.png\" alt=\"Directory\" />$Files</a>";
            $Size = "Directory"; //Set Size column to "Directory"
        } else {
            //This grabs whatever is after the last . in the filename. This is good for files that have multiple periods in it's name
            $last = substr(strrchr($Files, "."), 1); 
            //Create a list of file types to associate with icons
            $types = array("jpg", "jpeg", "gif", "png", "php", "mp3", "zip", "psd", "pdf"); 
            //If the filetype of the file in the directory matches one in the types array above then set an icon for it
            if(in_array($last, $types)) {
                switch($last) {
                    case "jpg":
                    case "jpeg":
                    case "gif":
                    case "pdf":
                    case "png": $image = "/i/image.png"; break;
                    case "psd": $image = "/i/psd.png"; break;
                    case "php": $image = "/i/php.png"; break;
                    case "mp3": $image = "/i/mp3.png"; break;
                    case "zip": $image = "/i/zip.png"; break;
                    default: $image = "/i/default.png"; break;
                }
            } else {
                //Set a default icon for the filetype
                $image = "/i/default.png"; 
            }
            //Create the link to the file with the icon
            $Name = "<a href=\"".$Filename."\"><img src=\"".$image."\" alt=\"".$last." File\" />$Files</a>";
            //Set the filesize of the file
            $filesize = filesize ($Filename);
            //Create friendly filesize
            $Size = have_filesize($filesize);
        }
    }
    //Set name, size and type arrays
    $FileArray[] = $Name;
    $SizeArray[] = $Size;
    $TypeArray[] = $Type;
}
//Close directory
closedir ($Open);

// Define the two ways to sort the directory contents
$Sort = $_GET['sort'];
switch ($Sort) {
    case "SortByLow":
        array_multisort ($SizeArray, SORT_ASC, $FileArray);
        $sorting = "?sort=SortByHigh";
        break;
    case "SortByHigh":
        array_multisort ($SizeArray, SORT_DESC, $FileArray);
        $sorting = "?sort=SortByLow";
        break;
    default:
        array_multisort ($TypeArray, $FileArray, $SizeArray);
        $sorting = "?sort=SortByLow";
        break;
}
$cl = "";
// Print out the contents of the directory
echo "<ul>\n";
echo "<li><span><a href=\"".$sorting."\">Size</a></span><a href=\"".$_SERVER['PHP_SELF']."\">Filename</a></li>\n";

for ($n = 0; $n < count($FileArray); $n++) {
//This is used to place a class in every other list item for a different colored background
if($num = is_float($n/2)) {
    $cl = " class=\"$cellcolor2\"";
} else { $cl=""; }
    if($SizeArray[$n] == "directory") {
        echo ("<li".$cl."><span>$SizeArray[$n]</span>$FileArray[$n]</li>\n");
    } else {
        echo ("<li".$cl."><span>$SizeArray[$n]</span>$FileArray[$n]</li>\n");
    }
}
echo "</ul>";
?>

<hr><h3>Spread the word -  <a href="Free_Netflicks_Sign_Print.pdf">download our free letter print sign!</a></h3>

<form novalidate action="index.php" method="post"><hr><h3>Suggestions?</h3>
<p class="warning"><?php echo !empty($err)?$err:"&nbsp;";?></p>
<input id="suggestion" type="text" name="suggestion" size="35" placeholder="Enter your suggestion!"><button type="Submit">Submit</button></p>
</form>
<script>document.onload = function() { document.getElementById("suggestion").focus();};</script>

<!--
<form enctype="multipart/form-data" action="index.php" method="post"><hr><h3>Add Your File</h3>
<input type="hidden" name="MAX_FILE_SIZE" value="100000" />
Choose a file to upload: <input name="uploadedfile" type="file" /><br />
<input type="submit" value="Upload File" />
</form>
-->
</body>
</html>