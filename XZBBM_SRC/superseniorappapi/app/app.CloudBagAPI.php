<?php
if (! defined ( "IN_SYS" ))
{
    header ( "HTTP/1.1 404 Not Found" );
    die ();
}
class CloudBagAPI extends Xzbbm
{
    public function __construct()
    {
        parent::__construct ();
    }
    
    /**
     *
     * @author XYJ
     *         收藏文件
     *         http://112.124.50.239/?action=CloudBagAPI&do=CollectFile&debug=on&dg=ml&msg={%22xztoken%22:%221c1523a6adbd65b17cd7425a871b1b0a%22,%22file_index%22:%225a6a559bd8f9d623c71f9c58465b6fcd%22}
     */
    public function CollectFile()
    {
        $user_id = $this->GetUser ()["userid"];
        $time = TIMESTAMP;
        $file_id = $this->msg ["file_index"];
        $remark = $this->msg ["remark"];
        if (! empty ( $file_id ))
        {
            $rs = $this->_db->rsArray ( "SELECT file_id FROM pd_files WHERE file_index = '$file_id' LIMIT 1" );
            $file_id = $rs ["file_id"];
        }
        else
        {
            $file_id = $this->msg ["file_id"];
        }
        
        $rs = $this->_db->rsArray ( "SELECT id FROM xz_cloudbag WHERE file_id = '$file_id' AND user_id = $user_id" );
        if (! empty ( $rs ))
        {
            $this->Error ( "不可重复收藏" );
        }
        else
        {
            $insert = array (
                    file_id => $file_id,
                    collect_time => $time,
                    user_id => $user_id,
                    remark => $remark,
                    sign => 0 
            );
            $rs = $this->_db->insert ( 'xz_cloudbag', $insert );
            
            if (is_numeric ( $rs ))
            {
                $this->ok ();
            }
            else
            {
                $this->say ( $insert . "\n[ERROR]" . $this->_db->_errorMsg );
                $this->Error ( "服务器出错，请稍后重试" );
            }
        }
    }
    
    /**
     * 创建文件夹
     *
     * @author xyj
     */
    public function CreateFolder()
    {
        $user_id = $this->GetUser ()["userid"];
        $dir = $this->msg ["dir"];
        $folder_name = $this->msg ["folder"];
        $name = $dir . "/" . $folder_name . "/";
        
        $rs = $this->_db->rsArray ( "SELECT id FROM xz_cloudbag WHERE sign = 1 AND dir = '$name' AND user_id = $user_id" );
        if (! empty ( $rs ))
        {
            $this->Error ( "文件夹名字不可重复" );
        }
        else
        {
            
            $insert = array (
                    'user_id' => $user_id,
                    'dir' => $name,
                    'sign' => 1 
            );
            
            $rs = $this->_db->insert ( "xz_cloudbag", $insert );
            if (is_numeric ( $rs ))
            {
                $this->ok ();
            }
            else
            {
                $this->say ( $insert . "\n[ERROR]" . $this->_db->_errorMsg );
                $this->Error ( "服务器出错，请稍后重试" );
            }
        }
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
        $user_id = $this->GetUser ()["userid"];
        $file_id = $this->msg ["file_id"];
        if (empty ( $file_id ))
        {
            $file_index = $this->msg ["file_index"];
            $file_id = $this->_db->rsArray ( "SELECT file_id FROM pd_files WHERE file_index = $file_index LIMIT 1" )["file_id"];
        }
        
        $update = array (
                'dir' => $after_dir 
        );
        $rs = $this->_db->update ( "xz_cloudbag", $update, " user_id = $user_id AND file_id = '$file_id' AND dir = '$before_dir'" );
        if ($rs == true)
        {
            $this->ok ();
        }
        else
        {
            $this->say ( $update . "\n[ERROR]" . $this->_db->_errorMsg );
            $this->Error ( "服务器出错，请稍后重试" );
        }
    }
    
    /**
     *
     * @author xyj
     *         取消收藏文件
     */
    public function UnCollectFile()
    {
        $user_id = $this->GetUser ()["userid"];
        $dir = $this->msg ["dir"];
        $file_id = $this->msg ["file_id"];
        if (empty ( $file_id ))
        {
            $file_index = $this->msg ["file_index"];
            $file_id = $this->_db->rsArray ( "SELECT file_id FROM pd_files WHERE file_index = $file_index LIMIT 1" )["file_id"];
        }
        
        $this->_db->delete ( "xz_cloudbag", "user_id = $user_id AND dir = '$dir' AND file_id = $file_id" );
    }
    /**
     *
     * @author xyj
     *         修改文件备注
     */
    public function RemarkFile()
    {
        $remark = $this->msg ["remark"];
        $user_id = $this->GetUser ()["userid"];
        $file_id = $this->msg ["file_id"];
        if (empty ( $file_id ))
        {
            $file_index = $this->msg ["file_index"];
            $file_id = $this->_db->rsArray ( "SELECT file_id FROM pd_files WHERE file_index = $file_index LIMIT 1" )["file_id"];
        }
        
        $update = array (
                remark => $remark 
        );
        
        $rs = $this->_db->update ( "xz_cloudbag", $update, "user_id = $user_id AND file_id = $file_id" );
        if ($rs == true)
        {
            $this->ok ();
        }
        else
        {
            $this->say ( $update . "\n[ERROR]" . $this->_db->_errorMsg );
            $this->Error ( "服务器出错，请稍后重试" );
        }
    }
    
    /**
     *
     * @author xyj
     *         修改文件夹名称
     *        
     */
    public function RenameFolder()
    {
        $after_dir = $this->msg ["after_dir"];
        $before_dir = $this->msg ["before_dir"];
        $user_id = $this->GetUser ()["userid"];
        $update = array (
                dir => $after_dir,
                user_id => $user_id 
        );
        $rs = $this->_db->update ( "xz_cloudbag", $update, " user_id = $user_id AND dir = '$before_dir' AND sign = 1" );
        if ($rs == true)
        {
            $this->ok ();
        }
        else
        {
            $this->say ( $update . "\n[ERROR]" . $this->_db->_errorMsg );
            $this->Error ( "服务器出错，请稍后重试" );
        }
    }
    
    /**
     *
     * @author xyj
     *         返回云书包的数据
     */
    public function GetCloudBagInfo()
    {
        $dir = $this->msg ["dir"];
        $user_id = $this->GetUser ()["userid"];
        
        $sql = "SELECT * FROM xz_cloudbag 
    	LEFT JOIN pd_files ON pd_files.file_id = xz_cloudbag.file_id 
    	WHERE xz_cloudbag.user_id=$user_id 
    	AND xz_cloudbag.dir = '$dir' 
    	AND sign = 0 ";
        $rs_file = $this->GetData ( $sql );
        
        $sql = "SELECT dir FROM xz_cloudbag WHERE user_id = $user_id AND sign = 1";
        $rs = $this->GetData ( $sql );
        
        $index = 0;
        foreach ( $rs as $k => $v )
        {
            if (substr_count ( $v ["dir"], "/" ) == (substr_count ( $dir, "/" ) + 1))
            {
                $rs_folder [$index] = $v;
                $index += 1;
            }
        }
        $this->Ret ( [ 
                "FileInfo" => $rs_file,
                "FolderInfo" => $rs_folder 
        ] );
    }
}