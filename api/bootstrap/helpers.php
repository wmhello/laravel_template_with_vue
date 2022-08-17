<?php
/**
 *
 */
function getDomain() {
     $url = request()->url();
     $path = request()->path();
     $domain = str_replace($path,"",  $url);
     return $domain;
}
    //压缩整个文件夹以及过滤
     function zipFolder($basePath,$relativePath,$zip)
    {
        $handler = opendir($basePath.$relativePath);  //打开当前文件夹
        while(($filename = readdir($handler))!==false){ //readdir() 函数返回目录中下一个文件的文件名
           if($filename != '.' && $filename != '..'){ //若文件为目录，则递归调用函数
                if(is_dir($basePath . $relativePath. '/' . $filename)){
                        zipFolder($basePath, $relativePath. '/' . $filename, $zip);
                }else{
                     $zip->addFile($basePath . $relativePath. '/' .$filename, $relativePath. '/' . $filename);
                }
            }
        }
        closedir($handler);
    }
