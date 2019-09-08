<?php
function delete_directory($dirname) {
    if (is_dir($dirname))
        $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname."/".$file))
                unlink($dirname."/".$file);
            else
                delete_directory($dirname.'/'.$file);
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}
if(isset($_POST['submitzip']))
{
    $zip=$_POST['zip'];
    $ziper = new ZipArchive;
    $res = $ziper->open($zip);
    if ($res === TRUE) {
        $ziper->extractTo(__DIR__);
        $ziper->close();
        $success=true;
        $msg="zip";
    } else {
        $success=false;
        $msg="zip";
    }
}
if(isset($_POST['submitdelete']))
{
    $deletes=$_POST['deletes'];
    foreach ($deletes as $delete)
    {
        $parts=explode('.',$delete);
        if(sizeof($parts)>1)
        {
            unlink($delete);
            if(!is_file($delete))
            {
                $success=true;
                $msg="delete";
            }
            else
            {
                $success=false;
                $msg="delete";
            }
        }
        else
        {
            if(delete_directory($delete))
            {
                $success=true;
                $msg="delete";
            }
            else
            {
                $success=false;
                $msg="delete";
            }
        }
    }
}
$lists=glob("*");
$files=array();
$zips=array();
foreach ($lists as $list)
{
    $parts=explode(".",$list);
    $dir=__FILE__;
    $fileparts=explode('\\',$dir);
    $filename=end($fileparts);
    if($list!=$filename)
    {
        array_push($files,$list);
        if(strtolower(end($parts))=='zip')
        {
            array_push($zips,$list);
        }
    }
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hostmanager</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container">
        <?php if(isset($success)):?>
        <div class="row">
            <div class="alert <?=($success)?'alert-success':'alert-danger';?>" style="width: 100%;margin-top: 20px;">
                <?php
                echo ($msg=='zip')?' Extraction ':' Deletation ';
                echo ($success)?' was successfull and this file was deleted':' failed! ';

                ?>
            </div>
        </div>
        <?php endif;?>
        <?php if(!isset($success)):?>
        <div class="row" style="margin-top: 20px;">
            <form method="post" style="width: 100%">
                <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xl-12  form-group">
                    <label>select file to extract</label>
                    <select class="form-control" name="zip" id="zip">
                        <option></option>
                        <?php foreach ($zips as $zip):?>
                            <option value="<?=$zip;?>"><?=$zip;?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xl-12  form-group">
                    <button class="btn btn-primary" type="submit" name="submitzip">submit</button>
                </div>
            </form>
        </div>
        <div class="row" style="margin-top: 20px;">
            <form method="post" style="width: 100%">
                <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xl-12  form-group">
                    <label>select files or directories to delete</label>
                    <select class="form-control" name="deletes[]" id="deletes" multiple>
                        <option></option>
                        <?php foreach ($files as $file):?>
                            <option value="<?=$file;?>"><?=$file;?></option>
                        <?php endforeach;?>
                    </select>
                </div>
                <div class="col-12 col-md-12 col-lg-12 col-sm-12 col-xl-12  form-group">
                    <button class="btn btn-primary" type="submit" name="submitdelete">submit</button>
                </div>
            </form>
        </div>
        <?php endif;?>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="  crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js" integrity="sha384-xrRywqdh3PHs8keKZN+8zzc5TX0GRTLCcmivcbNJWm2rs5C8PRhcEn3czEjhAO9o" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script>
    $('#zip').select2();
    $('#deletes').select2();
</script>
</html>
<?php
if(isset($success) and $success )
{
  unlink(__FILE__);
}
?>