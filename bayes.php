<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
error_reporting(1);
require_once __DIR__ . '/vendor/autoload.php';

// create library stemmer
$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
$stemmer  = $stemmerFactory->createStemmer();
$host="localhost";	//host name
$db="data";			//database name
$user="root";		//mysql username
$pass="";			//mysql password
$koneksi=mysql_connect($host,$user,$pass); //melakukan koneksi ke mysql
mysql_select_db($db);

function iklan($kata){
    $hasil=mysql_query("SELECT * FROM kata_train WHERE kata='$kata'");
    while($data=mysql_fetch_array($hasil) or die($data."<br/><br/>".mysql_error())){ //mengambil data dari database data training
    $bobot=$data['bobotiklan']*($data['iklan']/56)/(33/56);	//Memberi bobot PIklan|$kata
    return $bobot;
    
}

}
function noniklan($kata){
    $hasil=mysql_query("SELECT * FROM kata_train WHERE kata='$kata'");
    while($data=mysql_fetch_array($hasil) or die($data."<br/><br/>".mysql_error())){
    $bobot=$data['bobotnon']*($data['noniklan']/56)/(15/56);	
    return $bobot;
}

}
function hitungBayes($kata1){
    $kata= explode(" ", $kata1);
    $z=count($kata);
    $x=0;
    $bobotku[1]=0;
    $bobotku[2]=0;
    while ($x<$z){
        $jumlah= mysql_num_rows(mysql_query("SELECT * FROM kata_train WHERE kata='$kata[$x]'"));
        if ($jumlah==0){
            $bobot=0;
            $bobotnon=0;
        }
        else {
            $bobot=iklan($kata[$x]);
            $bobotnon=noniklan($kata[$x]);
        }
        $bobotku[1]=$bobotku[1]+$bobot;
        $bobotku[2]=$bobotku[2]+$bobotnon;
        $x++;
    
    }
    return $bobotku;
}

$katax[1]=strtolower($_GET['comment1']);
$katax[2]=strtolower($_GET['comment2']);
$katax[3]=strtolower($_GET['comment3']);
$katax[4]=strtolower($_GET['comment4']);
$katax[5]=strtolower($_GET['comment5']);
$katax[6]=strtolower($_GET['comment6']);
$katax[7]=strtolower($_GET['comment7']);
$katax[8]=strtolower($_GET['comment8']);
$katax[9]=strtolower($_GET['comment9']);
$katax[10]=strtolower($_GET['comment10']);

$kata[1]=$stemmer->stem($katax[1]);
$kata[2]=$stemmer->stem($katax[2]);
$kata[3]=$stemmer->stem($katax[3]);
$kata[4]=$stemmer->stem($katax[4]);
$kata[5]=$stemmer->stem($katax[5]);
$kata[6]=$stemmer->stem($katax[6]);
$kata[7]=$stemmer->stem($katax[7]);
$kata[8]=$stemmer->stem($katax[8]);
$kata[9]=$stemmer->stem($katax[9]);
$kata[10]=$stemmer->stem($katax[10]);
$x=0;$y=0;
for ($i=1;$i<=10;$i++){
    $bobot= hitungBayes($kata[$i]);
    if ($bobot[2]>=$bobot[1]){
        $x=$x+1;
        echo $i.'<i>'.$kata[$i].' </i><b>adalah Bukan Promosi</b></br>';
        
    }
    else{
        $y=y+1;
        echo $i.'<i>'.$kata[$i].' </i><b>adalah  Promosi</b></br>';
    }
}
