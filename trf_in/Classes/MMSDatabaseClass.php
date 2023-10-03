<?php

class MMSDatabaseClass
{
    
    //FOR MMS
    private $mms_host_ip      = "192.168.0.40";
    //test
    //private $mms_dbase        = "mmsmtsml";
    // prod
    private $mms_dbase        = "mmsmtsml";
    private $mms_uname        = "studentwhs";
    private $mms_pass         = "studentwhs";



    function __construct()
    {
        //FOR MMS CONNECTION
        $this->mmsconn = odbc_connect(
            "DRIVER=Client Access ODBC Driver (32-bit);
            System=".$this->mms_host_ip.";
            DBQ=".$this->mms_dbase, $this->mms_uname, $this->mms_pass
        );
    }

    // ============== METHOD START FOR MMS ================ //
    protected function mms_exec_query($mmsqry)
    {
        return odbc_exec($this->mmsconn,$mmsqry);
    }


    public function mms_get_rows($sp,$params)
    {
       
/*        $array = array();
        $qry = "call ".$this->mms_dbase.".".$sp."(".$params.")";
        $array[] = odbc_fetch_array($this-> mms_exec_query($qry));*/
        //$array[] = odbc_result_all($rs);
        //$array[] = odbc_fetch_array($rs);

        //return $array;
        
        //will return trf batch array

        $rows = array();
        $qry = "call ".$this->mms_dbase.".".$sp."(".$params.")";
        $result = $this-> mms_exec_query($qry);
        while($res = odbc_fetch_array($result))
        {
            $rows[] = $res;
        }

        return $rows;

    }

    //FUNCTION FOR GETTING UPC
    public function mms_get_upc($sp,$params)
    {
        /*$qry = "call ".$this->mms_dbase.".".$sp."(".$params.")";
        return odbc_fetch_array($this-> mms_exec_query($qry));*/

        $rows = array();
        $qry = "call ".$this->mms_dbase.".".$sp."(".$params.")";
        $result = $this-> mms_exec_query($qry);
        while($res = odbc_fetch_array($result))
        {
            $rows[] = $res;
        }

        return $rows;

        
    }

    public function mms_get_prim_upc($sku)
    {
        /*$qry = "call ".$this->mms_dbase.".".$sp."(".$params.")";
        return odbc_fetch_array($this-> mms_exec_query($qry));*/

        $rows = array();
        $qry = "call ".$this->mms_dbase.".getprimupc(".$sku.")";
        $result = $this-> mms_exec_query($qry);
        while($res = odbc_fetch_array($result))
        {
            $rows[] = $res;
        }

        return $rows;

        
    }


    public function mms_get_trf_data($trf)
    {
        $rows = array();
        $qry = "call ".$this->mms_dbase.".trf_data(".$trf.")";
        $result = $this-> mms_exec_query($qry);
        while($res = odbc_fetch_array($result))
        {
            $rows[] = $res;
        }

        return $rows;        
    }

    public function mmsDownload($sp,$trf)
    {
        return $this->mms_get_row($sp,$trf);
    }

    //FUNCTION TO GET STORE DETAILS
    public function mms_get_store()
    {
        $rows = array();
        $qry = "select strnum, strnam from tblstr";
        $result = $this-> mms_exec_query($qry);
        while($res = odbc_fetch_array($result))
        {
            $rows[] = $res;
        }

       return $rows;
    }

    public function get_iupcs($trfnum){
        $rows = array();
        $qry = "call ".$this->mms_dbase.".spgettrf1(".$trfnum.")";
        $result = $this-> mms_exec_query($qry);
        while($res = odbc_fetch_array($result))
        {
            $rows[] = $res;
        }

        return $rows;
    }

    // ============== METHOD END FOR MMS ================ //


}

//$obj = new MMSDatabaseClass();
//$dd = '12656978';
//print_r($obj->mms_get_prim_upc('3002748'));

/*$rex =$obj->get_iupcs('10143747');
    for ($y=0; $y < count($rex) ; $y++) { 
        $icmpno = $rex[$y]['ICMPNO'];
        $iupc = $rex[$y]['IUPC'];

        echo 'ICMPNO : '.$icmpno.' --- IUPC : '.$iupc.'<br>';
    }*/

//print_r($obj->mms_get_rows('spgettrf','12656978'));
