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
