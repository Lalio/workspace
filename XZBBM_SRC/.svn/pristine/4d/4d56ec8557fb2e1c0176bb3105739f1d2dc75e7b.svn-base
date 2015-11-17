<?php
if ( !defined( 'IN_SYS' ) ) {
	header( "HTTP/1.1 404 Not Found" );
	die;
}

class NewVersion extends Xzbbm{
    
	public function Index() {
		
		include Template('index','newxzbbm');
	}
	
	public function Search() {
	
	    include Template('search','newxzbbm');
	}
	
	public function Show() {

	    $sql = "SELECT file_id,file_name,pd_files.user_name,phone,email,user_icon,file_time,name,college,has_png,file_real_name,has_text 
	            FROM pd_files,pd_users,geo_universities,geo_colleges
	            WHERE file_key = '{$_REQUEST[file_key]}'
	            AND pd_files.ucode = geo_universities.university_id
	            AND pd_files.ccode = geo_colleges.college
	            AND pd_files.userid = pd_users.userid
	            LIMIT 0,1";

	    global $pagedata;
	    $pagedata = $this->GetData($sql);
	    $pagedata = $pagedata[0];
	    
	    //获取资料正文
	    if($pagedata['has_text'] == 1){
	        $pagedata['file_description'] = syssubstr(
	                                           file_get_contents(
	                                                  get_object($this->_oss,"$pagedata[file_real_name].txt")
	                                           ), 250);
	    }

	    include Template('show','newxzbbm');
	}
	
	public function User() {
	
	    include Template('user','newxzbbm');
	}

}
