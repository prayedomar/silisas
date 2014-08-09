<!DOCTYPE HTML>
<head>
    <meta http-equiv="content-type" content="text/html" />
    <meta name="author" content="www.renato.16mb.com" />
    <title>Subir Archivos En Codeigniter</title>
</head>
 
<body>
 
    <?=$mensaje;?>
    <?=form_open_multipart('upload/do_upload'); ?>
    <input type="file" name="userfile" size="20" />
    <br /><br />
    <input type="submit" value="upload" />
    </form>
 
</body>
</html>