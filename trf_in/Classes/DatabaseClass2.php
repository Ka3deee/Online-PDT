<?php

class DatabaseClass2
{
    

    
	
    //private $mysql_host_ip    = "10.0.3.47";
    //private $mysql_host_ip    = "10.6.0.63";
    private $mysql_host_ip    = "192.168.0.110";
    
    //private $mysql_uname      = "smrapp123";
    //private $mysql_pass       = "123";
    private $mysql_uname      = "root";
    private $mysql_pass       = "execadmin08";
    private $mysql_dbase      = "mtr_central";
    private $mysqlconn;




    function __construct()
    {
        //MYSQL CONNECTION
        $this->mysqlconn = mysqli_connect(
            $this->mysql_host_ip,
            $this->mysql_uname,
            $this->mysql_pass,$this->mysql_dbase) or die(mysqli_error("database")
        );  


    }

    // =============== METHOD START FOR MYSQL ================= //
    public function mysql_exec_query($sql)
    {
        return mysqli_query($this->mysqlconn,$sql);
    }

    protected function mysql_get_data($r)  
    {  
        $array = array();  
        while ($rows = mysqli_fetch_assoc($r))  
        {  
            $array[] = $rows;  
        }  
        return $array;  
    }  
    
    //fetching rows
    public function mysql_get_rows($fields,$like, $id = NULL, $tablename = NULL)
    {
        $col = empty($fields) ? '*' : implode(',',$fields);
        $id = empty($id) ? '' : ' WHERE '.$id;
        $wc = empty($like) ? '' : ' LIKE '.$id; 
        $qry = "SELECT $col FROM $tablename $id";  
        $results = $this->mysql_exec_query($qry);  
        $rows = $this->mysql_get_data($results);  
        return $rows;
    }

    //FOR VALIDATION OF ADMIN KEY
    public function isAdmin($mpass)
    {
        $md5_mpass = md5($mpass);
        $sql = "select * from users where secret_key = '".$md5_mpass."' and access_lvl=1"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        if(mysqli_num_rows($result) == 1){
            return true;
        }
         else{
            return false;
         }        
    }

    //FOR VALIDATION OF ADMIN KEY
    public function isDuplicate($ee)
    {
        $sql = "select * from users where eeid = '".$ee."' and access_lvl=0"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        if(mysqli_num_rows($result) >= 1){
            return true;
        }
         else{
            return false;
         }        
    }


    //FOR EE DETAIL
    public function geteeDetails($ee, $pword)
    {
        $sql = "select eeid, id from users where eeid = '".$ee."' and secret_key='".$pword."'"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        $json = [];
        while($row = mysqli_fetch_assoc($result)){
            $json[] = ['eeid'=>$row['eeid'],'uid'=>$row['id']];
        }
        return $json;    
    }

    //GET all stores
    public function mysql_get_stores()
    {
        $sql = "select id, store_code,store_desc from stores"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        $json = [];
        while($row = mysqli_fetch_assoc($result)){
            $json[] = ['id'=>$row['id'],'code'=>$row['store_code'],'desc'=>$row['store_desc']];
        }
        return $json;
    }

    protected function iregetTrf($barcode, $strcode, $ip){
        
    }

    //get trfs
    public function selectitrfs($barcode, $strcode, $ip)
    {
        //$sql = "call `spSearchTRF`('".$barcode."','".$refid."')";
        $trfbchtbl = $ip.'_batchtransfer_tbl';
        $iupctbl = $ip.'_iupc_tbl';
        $maintbl = $strcode.'_received_batch_trf_tbl';   
        $sql = "select x.id, x.trfbch,x.inumbr,x.idescr,x.expqty,x.rcvqty from (select b.id, b.trfbch,b.inumbr,b.idescr,b.expqty,c.rcvqty as rcvqty from `".$iupctbl."` as a
        inner join `".$trfbchtbl."` as b on a.inumbr = b.inumbr
        inner join `".$maintbl."` as c on a.inumbr = c.inumbr and b.trfbch = c.trfbch
        where b.strcode = '".$strcode."' and a.iupc = '".$barcode."' group by b.id, b.trfbch,b.inumbr,b.idescr,b.expqty) as x
        where x.expqty > x.rcvqty";
        $result = mysqli_query($this->mysqlconn,$sql);
        $json = [];
        while($row = mysqli_fetch_assoc($result)){
            $json[] = ['id'=>$row['inumbr'],'trfbch'=>$row['trfbch'],'inumbr'=>$row['inumbr'],'idescr'=>$row['idescr'],'expqty'=>$row['expqty'],'rcvqty'=>$row['rcvqty']];
        }
        return $json;
    }

    //FOR DUPLICATE
    public function checknoduplicate($ip,$trf,$store)
    {   
        $tblnam = $ip.'_batchtransfer_download_logs_tbl';
        $sql = "select * from `".$tblnam."` where trfbch = '".$trf."' and strcode='".$store."'"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        if(mysqli_num_rows($result) < 1){
            return true;
        }
         else{
            return false;
         }
    }

        //FOR DUPLICATE
    public function checknoduplicate3($trf,$store)
    { 
        $tblnam = $store.'_batchtransfer_status_tbl';
        $sql = "select * from `".$tblnam."` where trfbch = '".$trf."' and strcode='".$store."'"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        if(mysqli_num_rows($result) < 1){
            return true;
        }
         else{
            return false;
         }
    }

        //FOR DUPLICATE
    public function checknoduplicate2($trf,$store)
    {   
        $tblnam = $store.'_batchtransfer_status_tbl';
        $sql = "select * from `".$tblnam."` where trfbch = '".$trf."'"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        if(mysqli_num_rows($result) < 1){
            return true;
        }
         else{
            return false;
         }
    }

    //FOR DUPLICATE BARCODE AND IUPC
    public function checkbarcode($ip,$inumbr,$barcode)
    {   
        $tblnam = $ip.'_iupc_tbl';
        $sql = "select * from `".$tblnam."` where iupc like '%".$barcode."%' and inumbr = '".$inumbr."'"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        if(mysqli_num_rows($result) < 1){
            return true;
        }
         else{
            return false;
         }
    }

    //GET SPECIFIC TRF

    //GET SPECIFIC STORE
    public function mysql_get_trf_details($q)
    {
        $sql = "select id, inumbr, idescr from tranfiles where id = '".$q."'"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        $json = [];
        while($row = mysqli_fetch_assoc($result)){
            $json[] = ['id'=>$row['id'],'inumbr'=>$row['inumbr'],'idescr'=>$row['idescr']];
        }
        return $json;
    }    



    //GET SPECIFIC STORE
    public function mysql_get_store($q)
    {
        $sql = "select id, store_code,store_desc from stores where id = '".$q."'"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        $json = [];
        while($row = mysqli_fetch_assoc($result)){
            $json[] = ['id'=>$row['id'],'code'=>$row['store_code'],'desc'=>$row['store_desc']];
        }
        return $json;
    }

    //GET TRFS BY REFERENCE
    /*
    public function mysql_get_trfs($ip,$str)
    {
        $tblnam = $ip.'_batchtransfer_download_logs_tbl';
        $sql = "select id, trfbch from `".$tblnam."` where strcode = '".$str."'"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        $json = [];
        while($row = mysqli_fetch_assoc($result)){
            $json[] = ['id'=>$row['id'],'trfbch'=>$row['trfbch']];
        }
        return $json;
    }    */

    public function mysql_get_trfs($ip,$str)
    {
        $tblnam = $ip.'_batchtransfer_download_logs_tbl';
        $sql = "select id, trfbch from `".$tblnam."` where strcode = '".$str."'"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        $json = [];
        while($row = mysqli_fetch_assoc($result)){
            $json[] = ['id'=>$row['id'],'trfbch'=>$row['trfbch']];
        }
        return $json;
    }

    public function mysql_get_trfs_all($str)
    {
        $tblnam = $str.'_batchtransfer_status_tbl';
        $sql = "select distinct trfbch from `".$tblnam."` where isPrinted=0"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        $json = [];
        while($row = mysqli_fetch_assoc($result)){
            $json[] = ['trfbch'=>$row['trfbch'],'trfbch'=>$row['trfbch']];
        }
        return $json;
    }    

    //GET ALL REF
    public function mysql_get_refs($st)
    {
        $sql = "select id, ref_num from reference_ls where created_by = '".$st."' and isDone = 0"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        $json = [];
        while($row = mysqli_fetch_assoc($result)){
            $json[] = ['id'=>$row['id'],'ref_num'=>$row['ref_num']];
        }
        return $json;
    }

    //GET REFERENCE DETAIL

    public function mysql_get_ref_details($rf, $st)
    {
        $sql = "select id, ref_num, created_date, isDone from reference_ls where created_by = '".$st."' and id='".$rf."' and isDone = 0"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        $json = [];
        while($row = mysqli_fetch_assoc($result)){
            $json[] = ['id'=>$row['id'],'ref_num'=>$row['ref_num'],'created_date'=>$row['created_date'],'isDone'=>$row['isDone']];
        }
        return $json;      
    }


    //adding record
    public function mysql_add_row($col, $data, $table)
    {
        $qry = "insert into `".$table."`(".implode(',',$col).")
        values(".implode(',',$data).")";
        //echo $qry;
        $res = $this->mysql_exec_query($qry);
        if(!$res){
            return false;
            //echo 'saved';
        }else{
            return true;
            //echo 'failed';
        }

    }

    
    //FUNCTION FOR SSAVING SCANNED ITEM


    public function mysql_update_trf($ip,$strcode,$inumbr, $rcvqty,$trfnum)
    {
        $tblname = $ip.'_batchtransfer_tbl';
        $tblname2 = $strcode.'_received_batch_trf_tbl';
        $qry = "update `".$tblname."` set rcvqty = '".$rcvqty."' where inumbr='".$inumbr."' and strcode = '".$strcode."' and trfbch='".$trfnum."'";
        $qry2 = "update `".$tblname2."` set rcvqty = '".$rcvqty."' where inumbr='".$inumbr."' and trfbch='".$trfnum."'";
        $res = $this->mysql_exec_query($qry);
        $res2 = $this->mysql_exec_query($qry2);

        if(!$res || !$res2){
            return false;
            //echo 'saved';
        }else{
            //$this->add_to_logs($trfid, $qty, $userid, $date);
            return true;
            //echo 'failed';
        }        
    }

    protected function add_to_logs($trfid, $qty, $userid, $date)
    {
        $qry = "insert into scanlogs(`tranfiles_id`,`scanned_qty`,`scanned_by`,`date_scanned`)
        values('".$trfid."','".$qty."','".$userid."','".$date."')";
        $this->mysql_exec_query($qry);
    }


    //UPDATING DOWNLOADED_TRFS
    public function mysql_main_trfs($ip,$str,$trbch)
    {
        $tblname1 = $str.'_received_batch_trf_tbl';
        $tblname2 = $ip.'_batchtransfer_tbl';
        $tblname3 = $ip.'_batchtransfer_download_logs_tbl';

        $qry = "insert into `".$tblname1."`(`trfbch`,`inumbr`,`idescr`,`trfbch`,`istdpk`,`rcvqty`,`expqty`)
        select `trfbch`,`inumbr`,`idescr`,`trfbch`,`istdpk`,`rcvqty`,`expqty` from `".$tblname2."` where trfbch ='".$trfbch."'";
        
        if($this->mysql_exec_query($qry)){
            //delete from temp table
            $qry2 = "delete from `".$tblname2."` where trfbch='".$trfbch."' and strcode='".$str."'";
            if($this->mysql_exec_query($qry2)){
                $qry3 = "delete from `".$tblname3."` where trfbch='".$trfbch."' and strcode = '".$str."'";
                if($this->mysql_exec_query($qry3)){
                    return true;
                }
            }
        }else{
            return false;
        }
    }

    public function mysql_get_tranfiles($ip,$str,$trfbch)
    {
        $tblnam = $ip.'_batchtransfer_tbl';
        $sql = "select
        a.inumbr,
        a.idescr,
        a.trfshp,
        a.istdpk,
        a.rcvqty,
        a.expqty,
        a.trfbch
        from `".$tblnam."` as a
        where a.strcode = '".$str."' and a.trfbch = '".$trfbch."'"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        $json = array();
        while($row = mysqli_fetch_assoc($result)){
            $json[] = ['inumbr'=>$row['inumbr'],'idescr'=>$row['idescr'],'trfshp'=>$row['trfshp'],'istdpk'=>$row['istdpk'],'rcvqty'=>$row['rcvqty'],'expqty'=>$row['expqty']];
            //$json[] = ['inumbr'=>$row['inumbr'],'idescr'=>$row['idescr'],'trfshp'=>$row['trfshp'],'ist];
        }
        return $json;
    }    

    
    public function mysql_get_tranfiles_all($str,$trfbch)
    {
        $tblnam = $str.'_received_batch_trf_tbl';
        $sql = "select 
        a.inumbr,
        a.idescr,
        a.trfshp as trfshp,
        a.istdpk as istdpk,
        a.rcvqty as rcvqty,
        a.expqty as expqty,
        a.trfbch
        from `".$tblnam."` as a
        where a.trfbch = '".$trfbch."' group by a.inumbr, a.idescr, a.trfshp, a.istdpk, a.rcvqty,a.expqty, a.trfbch"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        $json = array();
        while($row = mysqli_fetch_assoc($result)){
            $json[] = ['inumbr'=>$row['inumbr'],'idescr'=>$row['idescr'],'trfshp'=>$row['trfshp'],'istdpk'=>$row['istdpk'],'rcvqty'=>$row['rcvqty'],'expqty'=>$row['expqty']];
            //$json[] = ['inumbr'=>$row['inumbr'],'idescr'=>$row['idescr'],'trfshp'=>$row['trfshp'],'ist];
        }
        return $json;
    }   

/*
    public function mysql_get_tranfiles_all($str,$trfbch)
    {
        $tblnam = $str.'_received_batch_trf_tbl';
        $sql = "select
        a.inumbr,
        a.idescr,
        a.trfshp,
        a.istdpk,
        a.rcvqty,
        a.expqty,
        a.trfbch
        from `".$tblnam."` as a
        where a.trfbch = '".$trfbch."'"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        $json = array();
        while($row = mysqli_fetch_assoc($result)){
            $json[] = ['inumbr'=>$row['inumbr'],'idescr'=>$row['idescr'],'trfshp'=>$row['trfshp'],'istdpk'=>$row['istdpk'],'rcvqty'=>$row['rcvqty'],'expqty'=>$row['expqty']];
            //$json[] = ['inumbr'=>$row['inumbr'],'idescr'=>$row['idescr'],'trfshp'=>$row['trfshp'],'ist];
        }
        return $json;
    }   */



    public function get_trfbch($ip,$str,$id){
        $tblnam = $ip.'_batchtransfer_download_logs_tbl';
        $sql = "select trfbch from `".$tblnam."` where id = '".$id."' and strcode = '".$str."'"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        $row = mysqli_fetch_assoc($result);
        return $row['trfbch'];
    }

    public function get_trfbch_all($str,$id){
        $tblnam = $str.'_received_batch_trf_tbl';
        $sql = "select trfbch from `".$tblnam."` where id = '".$id."'"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        $row = mysqli_fetch_assoc($result);
        return $row['trfbch'];
    }    


        //for control
    public function getQTY($trfid){
        $sql = "select rcvqty, expqty from tranfiles where id = '".$trfid."'"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        $row = mysqli_fetch_assoc($result);
        $json = array();
        $json[] = ['rcvqty'=>$row['rcvqty'],'expqty'=>$row['expqty']];

        return $json;
    }



    // FUNCTION :: FOR CREATING TEMPORARY TABLE
    protected function isTableExist($tablename){
        $sql = "SHOW TABLES FROM trfin_db LIKE '%".$tablename."%'"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        
        if(mysqli_num_rows($result) > 0){
            return true;
        }else{
            return false;
        }
    }

    // CHeck server table
    public function checkServerTable($serverip){
        $sql = "SHOW TABLES FROM trfin_db LIKE '%".$serverip."%'"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        
        if(mysqli_num_rows($result) > 0){
            return true;
        }else{
            return false;
        }
    }

    
    // check store table
    public function checkStoreTable($str){
        $sql = "SHOW TABLES FROM trfin_db LIKE '%".$str."%'"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        if(mysqli_num_rows($result) > 0){
            return true;
        }else{
            return false;
        }
    }


    public function createTempTable2($ip){
        $tblnam = $ip.'_iupc_tbl';
        if($this->isTableExist($tblnam) == false){
            $sql = "CREATE TABLE `".$ip."_iupc_tbl` (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY (id)) ENGINE=InnoDB SELECT * FROM `iupc_tbl`"; 
            mysqli_query($this->mysqlconn,$sql);
        }else{
            $sql = "TRUNCATE TABLE `".$tblnam."`";
            mysqli_query($this->mysqlconn,$sql);         
        }

        //CREATING BATCH TRANSFER TABLE
        $tblnam = $ip.'_batchtransfer_tbl';
        if($this->isTableExist($tblnam) == false){
            $sql = "CREATE TABLE `".$ip."_batchtransfer_tbl` (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY (id)) ENGINE=InnoDB SELECT * FROM `batchtransfer_tbl`"; 
            mysqli_query($this->mysqlconn,$sql);
        }else{
            $sql = "TRUNCATE TABLE `".$tblnam."`";
            mysqli_query($this->mysqlconn,$sql);
        }

        //CREATING BATCH TRANSFER LOG TABLE
        $tblnam = $ip.'_batchtransfer_download_logs_tbl';
        if($this->isTableExist($tblnam) == false){
            $sql = "CREATE TABLE `".$ip."_batchtransfer_download_logs_tbl` (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY (id)) ENGINE=InnoDB SELECT * FROM `batchtransfer_download_logs_tbl`"; 
            mysqli_query($this->mysqlconn,$sql);
        }else{
            $sql = "TRUNCATE TABLE `".$tblnam."`";
            mysqli_query($this->mysqlconn,$sql);    
        }
    }


    public function createTempTable($ip, $str){
        //CREATING IUPC TABLE

        $tblnam = $ip.'_iupc_tbl';
        if($this->isTableExist($tblnam) == false){
            $sql = "CREATE TABLE `".$ip."_iupc_tbl` (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY (id)) ENGINE=InnoDB SELECT * FROM `iupc_tbl`"; 
            mysqli_query($this->mysqlconn,$sql);
        }

        //CREATING BATCH TRANSFER TABLE
        $tblnam = $ip.'_batchtransfer_tbl';
        if($this->isTableExist($tblnam) == false){
            $sql = "CREATE TABLE `".$ip."_batchtransfer_tbl` (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY (id)) ENGINE=InnoDB SELECT * FROM `batchtransfer_tbl`"; 
            mysqli_query($this->mysqlconn,$sql);
        }
        //CREATING BATCH TRANSFER LOG TABLE
        $tblnam = $ip.'_batchtransfer_download_logs_tbl';
        if($this->isTableExist($tblnam) == false){
            $sql = "CREATE TABLE `".$ip."_batchtransfer_download_logs_tbl` (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY (id)) ENGINE=InnoDB SELECT * FROM `batchtransfer_download_logs_tbl`"; 
            mysqli_query($this->mysqlconn,$sql);
        }

        $tblnam = $str.'_received_batch_trf_tbl';
        if($this->isTableExist($tblnam) == false){
            $sql = "CREATE TABLE `".$str."_received_batch_trf_tbl` (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY (id)) ENGINE=InnoDB SELECT * FROM `received_batch_trf_tbl`"; 
            mysqli_query($this->mysqlconn,$sql);
        }

        $tblnam = $str.'_batchtransfer_status_tbl';
        if($this->isTableExist($tblnam) == false){
            $sql = "CREATE TABLE `".$str."_batchtransfer_status_tbl` (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY (id)) ENGINE=InnoDB SELECT * FROM `batchtransfer_status_tbl`"; 
            mysqli_query($this->mysqlconn,$sql);
        }


        $tblnam = $str.'_iupc_tbl';
        if($this->isTableExist($tblnam) == false){
            $sql = "CREATE TABLE `".$str."_iupc_tbl` (id INT NOT NULL AUTO_INCREMENT,PRIMARY KEY (id)) ENGINE=InnoDB SELECT * FROM `iupc_tbl`"; 
            mysqli_query($this->mysqlconn,$sql);
        }
    }


    //migrate data from server to pdt
    public function migrateData($servertbl, $pdttbl){
        $sql = "insert into `".$pdttbl."` select * from `".$servertbl."`";
        mysqli_query($this->mysqlconn,$sql);
    }

    public function getStore($str){
        $sql = "select * from stores where store_code = '".$str."'"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);

            $json = array();
            $json = ['store_code'=>$row['store_code'],'store_desc'=>$row['store_desc']];
            return $json;
        }else{
            $json = ['store_code'=>"",'store_desc'=>""];
            return $json;
        }
    }

    public function getUPC($ip,$inumbr){
        $tblnam = $ip.'_iupc_tbl';
        $sql = "select iupc from `".$tblnam."` where inumbr = '".$inumbr."'"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);

            $json = array();
            $json = ['iupc'=>$row['iupc']];
            return $json;
        }else{
            $json = ['iupc'=>""];
            return $json;
        }
    }

    public function getUPCmain($str,$inumbr){
        $tblnam = $str.'_iupc_tbl';
        $sql = "select iupc from `".$tblnam."` where inumbr = '".$inumbr."'"; 
        $result = mysqli_query($this->mysqlconn,$sql);
        if(mysqli_num_rows($result) > 0){
            $row = mysqli_fetch_assoc($result);

            $json = array();
            $json = ['iupc'=>$row['iupc']];
            return $json;
        }else{
            $json = ['iupc'=>""];
            return $json;
        }
    }

    public function clearData(){
        $sql = "CALL spClearData"; 
        $isSuccess = mysqli_query($this->mysqlconn,$sql);
        if(!$isSuccess){
            return false;
        }else{
            return true;
        }        
    }

    //public function getMTR($str, $mtr){
    public function getMTR($mtr){
        //$sql = "select a.mtr_no, a.plateNo, a.deliveryDate, a.preparedBy, a.warehouse, a.timestamp, b.transfer_no from tblmtr_hdr as a left join tblmtr_dtl as b on a.mtr_no = b.mtr_no where a.warehouse = ".$str." and a.mtr_no='".$mtr."'";
        $sql = "select a.mtr_no, a.plateNo, a.deliveryDate, a.preparedBy, a.warehouse, a.timestamp, b.transfer_no from tblmtr_hdr as a left join tblmtr_dtl as b on a.mtr_no = b.mtr_no where a.mtr_no='".$mtr."'";
        $result = mysqli_query($this->mysqlconn,$sql);
        //$row = mysqli_fetch_assoc($result);
        $json = array();
        while($row = mysqli_fetch_assoc($result)){
            $json[] = [
                'mtr_no'=>$row['mtr_no'],
                'plate_no'=>$row['plateNo'],
                'delivery_date'=>$row['deliveryDate'],
                'prepared_by'=>$row['preparedBy'],
                'warehouse'=>$row['warehouse'],
                'timestamp'=>$row['timestamp'],
                'transfer_no' => $row['transfer_no']
            ];
        }
        

        return $json;
    }

    public function updateMTRCentralHeaderCIP($mtrno,$stat){
        $sql = "update tblmtr_hdr set status=$stat where mtr_no = '".$mtrno."'";
        $isSuccess = mysqli_query($this->mysqlconn,$sql);
        if(!$isSuccess){
            return false;
        }else{
            return true;
        }    
    }

    public function updateMTRCentralHeaderCIPP($countToDeduct, $mtrno){
        $sql = "update tblmtr_hdr set cip = cip - " . intval($countToDeduct) . " WHERE mtr_no = '" . $mtrno. "'";
        $isSuccess = mysqli_query($this->mysqlconn,$sql);
        if(!$isSuccess){
            return false;
        }else{
            return true;
        }    
    }

    public function updateBatch($transfer_no){
        $sql = "UPDATE `tblmtr_dtl` SET is_uploaded=1 WHERE transfer_no ='".$transfer_no."'";
        mysqli_query($this->mysqlconn,$sql);
    }

    // =============== METHOD END FOR MYSQL ================= //


}

//$obj = new DatabaseClass();
//print_r($obj->getUPC('::1','3859509'));