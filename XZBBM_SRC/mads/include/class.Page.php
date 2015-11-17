<?php
/**
 *	@name:	class.Page.php
 *	@todo: 	分页类
 *	<code>
 		$page = new Page();
		$page->Setvar(array('order'=>$orderby,'category'=>$cat_id));
		$page->Set(LIST_PER_ROWS, $total_num);
		echo $page->Output(true, true);
	</code>
 *	@author:	seamaidmm 2010-10-29
 *
 */
 
 class Page{

    /**
     * 页面输出结果
     *
     * @var string
     */
    var $output;

    /**
     * 使用该类的文件,默认为 PHP_SELF
     *
     * @var string
     */
    var $file;

    /**
     * 页数传递变量，默认为 'p'
     *
     * @var string
     */
    var $pvar = "page";

    /**
     * 页面大小
     *
     * @var integer
     */
    var $psize;

    /**
     * 当前页面
     *
     * @var ingeger
     */
    var $curr;

    /**
     * 要传递的变量数组
     *
     * @var array
     */
    var $varstr;

    /**
     * 总页数
     *
     * @var integer
     */
    var $tpage;
    
 /**
     * 前台分页设置
     *
     * @access public
     * @param int $pagesize 页面大小
     * @param int $total    总记录数
     * @param int $current  当前页数，默认会自动读取
     * @return void
     */
    function SetAjax($sortid,$order,$pagesize=20,$total,$current,$target='_self', $rewrite=false) {
    	
        $show_num = 3;	//显示几个翻页按钮

        $this->tpage = ceil($total/$pagesize);
        if (!$current) {$current = isset($_REQUEST[$this->pvar])?$_REQUEST[$this->pvar]:1;}
        if ($current>$this->tpage) {$current = $this->tpage;}
        if ($current<1) {$current = 1;}

        $this->curr  = $current;
        $this->psize = $pagesize;
        if (!$this->file) {$this->file = $_SERVER['PHP_SELF'];}
        
        if ($this->tpage >= 1) {

            if ($current>1) {
                $this->output.='<a href="javascript:;" onclick="AsynGetData('.$sortid.',\''.$order.'\',1,'.$pagesize.')" title="首页">首页  </a>';
            }
            if ($current>1) {
                $this->output.='<a href="javascript:;" onclick="AsynGetData('.$sortid.',\''.$order.'\','.($current-1).','.$pagesize.')" title="上页">上页 </a>';
            }

            if($current > $show_num / 2) {
            	$this->output .= '...';
            }

			$start  = $current-floor($show_num/2);
            $end    = $current+ceil($show_num/2);

            if ($start<1)            {$start=1;}
            if ($end>$this->tpage)    {$end=$this->tpage;}

            for ($i=$start; $i<=$end; $i++) {
                if ($current==$i) {
                    $this->output.='<span class="mod56_page_pn_current">'.$i.'</span>';    //输出当前页数
                } else {
                    $this->output.='<a href="javascript:;" onclick="AsynGetData('.$sortid.',\''.$order.'\','.$i.','.$pagesize.')">'.$i.'</a>';    //输出页数
                }
            }

            if($this->tpage - $current > $show_num / 2) {
            	$this->output .= '...';
            }
            
            if ($current<$this->tpage) {
                $this->output.='<a href="javascript:;" onclick="AsynGetData('.$sortid.',\''.$order.'\','.($current+1).','.$pagesize.')" title="下页">下页 </a>';
            }
            if ($this->tpage > $current) {
                $this->output.='<a href="javascript:;" onclick="AsynGetData('.$sortid.',\''.$order.'\','.$this->tpage.','.$pagesize.')" title="尾页">尾页</a>';
            }
            
            $this->totalPage = '<div class="mod56_page_total"><span class="mod56_page_total_page">共'.$this->tpage.'页  </span></div>';
            
        }
    }
    
     /**
     * 后台分页设置
     *
     * @access public
     * @param int $pagesize 页面大小
     * @param int $total    总记录数
     * @param int $current  当前页数，默认会自动读取
     * @return void
     */
    function SetAdmin($pagesize=20,$total,$current=false,$target='_self', $rewrite=false) {
    	
    	if($rewrite){
    		$gue1 = '/';$gue2='_';$gue3="-";			
		}else{
			$gue1 = '?';$gue2='&';$gue3="=";
		}
	
        $show_num = 3;	//显示几个翻页按钮

        $this->tpage = ceil($total/$pagesize);
        if (!$current) {$current = isset($_REQUEST[$this->pvar])?$_REQUEST[$this->pvar]:1;}
        if ($current>$this->tpage) {$current = $this->tpage;}
        if ($current<1) {$current = 1;}

        $this->curr  = $current;
        $this->psize = $pagesize;

        if (!$this->file) {$this->file = $_SERVER['PHP_SELF'];}
        
        if ($this->tpage >= 1) {

            if ($current>1) {
                $this->output.='<a href="./?'.($this->varstr).$gue2.$this->pvar.$gue3.'1'.'" title="首页" target="'.$target.'">首页  </a>';
            }
            if ($current>1) {
                $this->output.='<a href="./?'.($this->varstr).$gue2.$this->pvar.$gue3.($current-1).'" title="上页" target="'.$target.'">上页 </a>';
            }

            if($current > $show_num / 2) {
            	$this->output .= '...';
            }

			$start  = $current-floor($show_num/2);
            $end    = $current+ceil($show_num/2);

            if ($start<1)            {$start=1;}
            if ($end>$this->tpage)    {$end=$this->tpage;}

            for ($i=$start; $i<=$end; $i++) {
                if ($current==$i) {
                    $this->output.='<span class="current"><a href="javascript:"><font color="#FF3300">'.$i.'</font></a></span>';    //输出当前页数
                } else {
                    $this->output.='<a href="./?'.$this->varstr.$gue2.$this->pvar.$gue3.$i.'" target="'.$target.'">'.$i.'</a>';    //输出页数
                }
            }

            if($this->tpage - $current > $show_num / 2) {
            	$this->output .= '...';
            }
            
            if ($current<$this->tpage) {
                $this->output.='<a href="./?'.($this->varstr).$gue2.$this->pvar.$gue3.($current+1).'" title="下页" target="'.$target.'">下页 </a>';
            }
            if ($this->tpage > $current) {
                $this->output.='<a href="./?'.($this->varstr).$gue2.$this->pvar.$gue3.$this->tpage.'" title="尾页" target="'.$target.'">尾页</a></div>';
            }
            
            $this->totalPage = '<div class="manu"><span>共'.$total.'条数据  第'.$this->curr.'/'.$this->tpage.'页  </span>';
            
        }
    }
    
    /**
     * 要传递的变量设置
     *
     * @access public
     * @param array $data   要传递的变量，用数组来表示，参见上面的例子
     * @return void
     */
    function Setvar($data, $rewrite=false) {
        if (!$omit) {
            $omit = array($this->pvar);
        }
        foreach ($_GET as $k=>$v)	if (!in_array($k,$omit))  $this->varstr .= $k.'='.urlencode($v).'&';
        if($append)  foreach ($append as $k=>$v) $this->varstr .= $k.'='.urlencode($v).'&';   

        //截掉右边的&,避免重复.
        $this->varstr = rtrim($this->varstr,'&');
        
        return $this->varstr;
    }

    /**
     * 分页结果输出
     *
     * @access public
     * @param bool $return 为真时返回一个字符串，否则直接输出，默认直接输出
     * @return string
     */
    function Output($return = false, $showInput=false) {
        $output = $showInput?($this->totalPage.$this->output.$this->inputPageNum):$this->totalPage.$this->output;
        if ($return) {
            return $output;
        } else {
            echo $output;
        }
    }
    
     public function getTotalPage() {
        return $this->tpage;
    }    

    /**
     * 生成Limit语句
     *
     * @access public
     * @return string
     */
    function Limit() {
        return (($this->curr-1)*$this->psize).','.$this->psize;
    }

 }
?>