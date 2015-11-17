<?php
if (! defined ( "IN_SYS" ))
{
    header ( "HTTP/1.1 404 Not Found" );
    die ();
}
class CloundBag extends Xzbbm
{
    public function __construct()
    {
        parent::__construct ();
    }
    
    /**
     *
     * @author XYJ
     *         收藏文件
     */
    public function CollectFile()
    {
        $user_id = $this->GetUser ()["userid"];
        $time = TIMESTAMP;
        $file_index = $this->msg ["file_index"];
        $remark = $this->msg ["remark"];
        
        if (! empty ( $file_index ))
        {
            $rs = $this->_db->rsArray ( "SELECT file_id FROM pd_files WHERE file_index = $file_index LIMIT 1" );
            $file_id = $rs ["file_id"];
        }
        else
        {
            $file_id = $this->msg ["file_id"];
        }
        $insert = array (
                file_id => $file_id,
                collect_time => $time,
                user_id => $user_id,
                remark => $remark,
                sign => 0 
        );
        $this->_db->insert ( "xz_cloudbag", $insert );
    }
    
    /**
     * 创建文件夹
     *
     * @author xyj
     */
    public function CreateFloder()
    {
        $user_id = $this->GetUser ()["userid"];
        $dir = $this->msg ["dir"];
        $folder_name = $this->msg ["folder"];
        $name = $dir . "/" . $folder_name;
        
        $insert = array (
                user_id => $user_id,
                name => $name,
                sign => 1 
        );
        
        $this->_db->insert ( "xz_cloudbag", $insert );
    }
    
    /**
     * 移动文件
     *
     * @author xyj
     */
    public function MoveFile()
    {
        $before_dir = $this->msg ["before_dir"];
        $after_dir = $this->msg ["after_dir"];
        $user_id = $this->GetUser ()["user_id"];
        $file_id = $this->msg ["file_id"];
        if (empty ( $file_id ))
        {
            $file_index = $this->msg ["file_index"];
            $file_id = $this->_db->rsArray ( "SELECT file_id FROM pd_files WHERE file_index = $file_index LIMIT 1" );
        }
        
        $update = array (
                name => $after_dir 
        );
        $this->_db->update ( "xz_cloudbag", $update, "user_id = $user_id AND file_id = $file_id AND name = $before_dir" );
    }
    
    /**
     *
     * @author xyj
     *         删除文件
     */
    public function DeleteFile()
    {
        $user_id = $this->GetUser ()["user_id"];
        $dir = $this->msg ["dir"];
        $file_id = $this->msg ["file_id"];
        if (empty ( $file_id ))
        {
            $file_index = $this->msg ["file_index"];
            $file_id = $this->_db->rsArray ( "SELECT file_id FROM pd_files WHERE file_index = $file_index LIMIT 1" );
        }
        
        $this->_db->delete ( "xz_cloudbag", "user_id = $user_id AND name = $dir AND file_id = $file_id" );
    }
    /**
     *
     * @author xyj
     *         修改文件备注
     */
    public function RemarkFile()
    {
        $remark = $this->msg ["remark"];
        $user_id = $this->GetUser ()["user_id"];
        $file_id = $this->msg ["file_id"];
        if (empty ( $file_id ))
        {
            $file_index = $this->msg ["file_index"];
            $file_id = $this->_db->rsArray ( "SELECT file_id FROM pd_files WHERE file_index = $file_index LIMIT 1" );
        }
        
        $update = array (
                remark => $remark 
        );
        
        $this->_db->update ( "xz_cloudbag", $update, "user_id = $user_id AND file_id = $file_id" );
    }
    
    /**
     * @author xyj
     * 返回云书包的数据
     */
    public function GetCloudBagInfo(){
    	$user_id = $this->GetUser()["user_id"];
    	$sql = "SELECT file_id,collect_time,remark,dir FROM xz_cloudbag WHERE sign = 0 AND user_id = $user_id";
    	$rs = $this->GetData($sql);
        
        
    }
    
}