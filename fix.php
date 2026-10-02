<?php $file="public/.htaccess";$content=file_get_contents($file);$content=str_replace("% { ","%{",$content);$content=str_replace(" }","}",$content);file_put_contents($file,$content);echo "fixed";?>
