<?php

function readtxtfile(){
    $stream = fopen("../serverip.txt", "r");
    while(($line=fgets($stream))!==false) {
        return $line; 
    }
}
