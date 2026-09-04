<?php
// File Name :category-helper.php
// Created on :15/10/2014
// Created By :

class CategoryHelper extends Helper
{

	private function __construct()
	{
		// constructor -> not needed
	}

	static function get_category_exists($cid)
	{
		$id=DAL::get_instance()->read_single_column("select id from ".TABLE_PREFIX."categories where id=?",array($cid));

		if($id !="")
			return true;
		else
			return false;
	}

	static function get_category_level($pid)
	{
		if($pid >0)
			return DAL::get_instance()->read_single_column("select level from ".TABLE_PREFIX."categories where id=?",array($pid));
		else
			return 0;
	}

	static function get_category_pid($cid)
	{
		return DAL::get_instance()->read_single_column("select pid from ".TABLE_PREFIX."categories where id=?",array($cid));
	}

	static function get_category_name($catid)
	{
		return DAL::get_instance()->read_single_column("select name from ".TABLE_PREFIX."categories where id=?",array($catid));
	}

	static function get_category_child_count($cid)
	{
		$db= DAL::get_instance();

		$count=$db->read_single_column("select count(id) from ".TABLE_PREFIX."categories where pid=?",array($cid));

		if($count >0)
			return $count;
		else
			return 0;

	}





	static function get_category_seo_name($data)
	{
        $data = str_replace(array(' ','(',')','&',',',':','/','|','{','}','[',']','\\','"','\'','<','>','%'),'-',$data);

        while(strpos($data,"--") >0)
        {
            $data = str_replace("--","-",$data);
        }

        if(substr($data,-1,1)=='-')
        $data=substr($data,0,-1);

        return strtolower($data);
	}

	static function get_category_list($categories)
	{
			$categoryArray = explode("," , $categories);

			$categoryDiv   = "";

			foreach($categoryArray as $catKey => $catValue)
			{
					$catValue     = trim($catValue);

					if($catValue != "")
					{
							$categoryName = CategoryHelper::get_category_name($catValue);
							$categoryDiv.= "<div class='category-list-div'>".$categoryName."</div>";
					}
			}

			if($categoryDiv == "")
			$categoryDiv = "-";

			return $categoryDiv;
	}

	static function get_category_list_api($categories)
	{
			$categoryArray = explode("," , $categories);

			$categoryDiv   = array();

			foreach($categoryArray as $catKey => $catValue)
			{
					$catValue      = trim($catValue);

					if($catValue != "")
					$categoryDiv[] = CategoryHelper::get_category_name($catValue);
			}

			if(count($categoryDiv) == 0)
			$categoryDiv[]     = "-";

			return $categoryDiv;
	}


	static function get_category_path($id,$url = "",$seoname = "",$option = "",$tab=1)
	{
		$path_str="";
		$path_str_new="";

		if($id ==0)
		return $path_str;

		$db= DAL::get_instance();


		$cpdseo=$seoname;

		$i=0;
		while(1)
		{
			$res=$db->execute_query("select name,pid from ".TABLE_PREFIX."categories where id=?",array($id));
			$row=$res->fetch_assoc();

			$orgid=$id;
			$id=$row['pid'];

			$path_str1="";
			$path_str_new1="";


			if($row['pid'] !=0)
			{
				if($url !="")
				{
					if($url == 'dispatch/sponsored/32')
					{
						if($cpdseo !='')
						$urldata=BASE.$cpdseo.'/'.$orgid.'/'.self::get_category_seo_name($row['name']).'/'.$option;
						else
						$urldata=self::make_url($url.'/'.$orgid.'/'.self::get_category_seo_name($row['name']).'/'.$option);
					}
					else
					$urldata=self::make_url($url.$orgid."/".$tab);


					$path_str1= '<a href="'.$urldata.'"><span ';

					if($i ==0)
					$path_str1.= 'class="catBold"';

					$path_str1.='>'.$row['name'].'</span></a>';
				}
				else
				{
					$path_str1= '<span ';

					if($i ==0)
					$path_str1.= 'class="catBold"';

					$path_str1.='>'.$row['name'].'</span>';
				}
				$path_str=$path_str1.$path_str;
			}
			else
			{
				if($url !="")
				{
					if($url == 'dispatch/sponsored/32')
					{
						if($cpdseo !='')
						$urldata=BASE.$cpdseo.'/'.$orgid.'/'.self::get_category_seo_name($row['name']).'/'.$option;
						else
						$urldata=self::make_url($url.'/'.$orgid.'/'.self::get_category_seo_name($row['name']).'/'.$option);
					}
					else
					$urldata=self::make_url($url.$orgid."/".$tab);


					$path_str_new1= '<a href="'.$urldata.'"><span ';

					if($i ==0)
					$path_str_new1.= 'class="catBold"';

					$path_str_new1.= '>'.$row['name'].'</span></a>';
				}
				else
				{
					$path_str_new1= '<span ';

					if($i ==0)
					$path_str_new1.= 'class="catBold"';

					$path_str_new1.= '>'.$row['name'].'</span>';
				}
				$path_str_new=$path_str_new1;
			}

			if($row['pid']!=0)
			{
				if($url!="")
				$path_str=" <span><span class='catArrow'>&raquo;</span>".$path_str."</span>";
				else
				$path_str=" <span><span>&raquo;</span>".$path_str."</span>";
			}
			else
			{
				return $path_str_new."</span>".$path_str;
			}
			$i=$i+1;
		}
	}


	static function get_category_dropdown($id=0,$i=0,$cid=0,$iab=0)
	{
		$db=DAL::get_instance();
		$cat_value="";

		if($iab == 1)
		$iab_condition=" and built_in_category = 1 ";
		else
		$iab_condition="";
		$res=$db->execute_query("select id,name from ".TABLE_PREFIX."categories where pid=? and category_status =? ".$iab_condition." order by name",array($id,1));

		while($row=$res->fetch_row())
		{
			$tot=$db->read_single_column("select count(id) from ".TABLE_PREFIX."categories where pid=? and category_status =?  ".$iab_condition,array($row[0],1));

			if($cid != $row[0])
			$cat_value=$cat_value.'<option value="'.$row[0].'">';
			else
			$cat_value=$cat_value.'<option selected="selected" value="'.$row[0].'">';

			for($count=0;$count < $i;$count++)
			{
				$cat_value=$cat_value.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;';
			}

			$cat_value=$cat_value.$row[1].'</option>';

			if($tot!=0)
			$cat_value=$cat_value.self::get_category_dropdown($row[0],$i+1,$cid);
		}

		return $cat_value;
	}



	static function get_category_ads_dropdown($pid=0,$selected=0)
	{
		$db=DAL::get_instance();
		global $drpstr;

		$level=$db->execute_query("SELECT id,name,pid,level FROM ".TABLE_PREFIX."categories WHERE pid=? and category_status =? ORDER BY name ASC",array($pid,1));
		while($leveldata=$level->fetch_assoc())
		{
				$stepstr="";
				for($i=0;$i< $leveldata['level'];$i++)
				{
					$stepstr.="&nbsp;&nbsp;&nbsp;&nbsp;";

					if($i ==$leveldata['level']-1)
					$stepstr.="&nbsp;&nbsp;&raquo;&nbsp;";
				}

				$idcount=$db->read_single_column("SELECT COUNT(id) FROM ".TABLE_PREFIX."categories WHERE pid=? ",array($leveldata['id']));

				if($idcount ==0)
				$lastlevelflag=1;
				else
				$lastlevelflag=0;

				if($lastlevelflag==1)
				{
					self::get_category_ads_dropdown($leveldata['id'],$selected);

					if($selected ==$leveldata['id'])
					$selectstr="selected";
					else
					$selectstr="";

					if($leveldata['pid']==0)
					$drpstr.="<option value='".$leveldata['id']."' ".$selectstr.">".$stepstr.$leveldata['name']."</option>";
					else
					$drpstr.="<option value='".$leveldata['id']."' ".$selectstr.">&nbsp;&nbsp;".$stepstr.$leveldata['name']."</option>";
				}
				else
				{
					if($leveldata['pid']==0)
					$drpstr.="<option disabled>".$stepstr.$leveldata['name']."</option>";
					else
					$drpstr.="<option disabled>"."&nbsp;&nbsp;".$stepstr.$leveldata['name']."</option>";

					self::get_category_ads_dropdown($leveldata['id'],$selected);
				}
		}
		return $drpstr;
	}



	static function get_site_exists($sid,$pid=0)
	{
		$string='';
		$arr=array();
		$arr[]=$sid;
		if($pid >0)
		{
			$string=' AND pid=? ';
			$arr[]=$pid;
		}


		$id=DAL::get_instance()->read_single_column("select id from ".TABLE_PREFIX."sites where id=? ".$string." ",$arr);

		if($id !="")
		return true;
		else
		return false;
	}

	static function get_site_owner($pid)
	{
		if($pid >0)
		$username=DAL::get_instance()->read_single_column("select username from ".TABLE_PREFIX."users where id=?",array($pid));
		else
		$username=self::get_label('admin');

		return $username;
	}



	static function get_site_dropdown($pid,$sid=0,$type=0,$cpd=0)
	{
		$db=DAL::get_instance();

		$statusstr  = "";
		$cpd_string = "";


		if($type == 0)
		$field_name ="sid";
		else
		{
			if($cpd ==0)
			$field_name ="sid_select_00";
			else if($cpd ==1)
			$field_name ="sid_select_01";
		}


		if($cpd ==1)
		$cpd_string = " AND marketplace_display=1 ";


		$res=$db->execute_query("SELECT id,url FROM ".TABLE_PREFIX."sites WHERE pid=? AND (status=1 OR status=0) ".$cpd_string." ",array($pid));

		$string='';
		$string.='<select class="form-select" aria-label="get site dropdown" name="'.$field_name.'" id="'.$field_name.'">';
		$string.='<option value="0">'.self::get_label('select site').'</option>';
		while($row=$res->fetch_assoc())
		{
			$string.='<option value="'.$row['id'].'" ';
			if($sid == $row['id'])
			$string.=' selected ';

			$string.='>'.$row['url'].'</option>';
		}
		$string.='</select>';

		return $string;
	}


	static function get_site_count_user($pid)
	{
		$db=DAL::get_instance();

		$count=$db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."sites WHERE pid=? AND status=1",array($pid));

		return $count;
	}

	static function get_site_name($sid,$status = 0,$protocol = 0)
	{
		$db = DAL::get_instance();

		$statusstr = '';

		if($status ==1)
		$statusstr=' AND status=1 ';

		$row = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."sites WHERE id=? ".$statusstr." ",array($sid));

		if($row->get_num_records() > 0)
		{
			$rowData = $row->fetch_assoc();
			$name    = $rowData['url'];
		}
		else
		$name = self::get_label('na');

		return $name;
	}

	static function get_category_child_list($pid, $str = "", $initialParent = 0)
	{
			if(!$initialParent)
			$initialParent = $pid;

			$displaySitesCount = intval(Configuration::get_instance()->read("display_active_site_count_with_category"));

			$db  = DAL::get_instance();
			$row = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."categories WHERE pid=? ORDER BY name",array($pid));

			while($row_data = $row->fetch_assoc())
			{
					$lestr      = "";
					$childcount = self::get_category_child_count($row_data['id']);

					for($i=0;$i < $row_data['level'];$i++)
					{
					    $lestr.= '&nbsp;&nbsp;&nbsp;';
				  }

					$str.='<div class="form-check">'.$lestr.'<input class="form-check-input" type="checkbox" parentid="'.$initialParent.'" name="ca'.$row_data['id'].'" id="ca'.$row_data['id'].'" value="1" /><label class="form-check-label">'.$row_data['name'];

					if($displaySitesCount == 1)
					$str.='<span class="site-count-span">&nbsp;('.CategoryHelper::get_category_sites_count($row_data['id']).')</span>';

					if($childcount >0)
					$str.=self::get_category_child_list($row_data['id'], "", $initialParent);

					$str.='</label></div>';
			}

			return $str;
	}


	static function get_category_child_for_website($pid, $str = "", $categoryArray = array())
	{
			$db  = DAL::get_instance();
			$row = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."categories WHERE pid=? ORDER BY name",array($pid));

			while($row_data=$row->fetch_assoc())
			{
					$lestr      = "";
					$childcount = self::get_category_child_count($row_data['id']);

					for($i=0;$i < $row_data['level'];$i++)
					{
				    	$lestr.= '&nbsp;&nbsp;&nbsp;';
			    }

					$checkedString = "";

					if(in_array($row_data['id'] , $categoryArray))
					$checkedString = ' checked="checked" ';

					$str.= '<div class="form-check"><span class="float-start">'.$lestr.'</span><input class="form-check-input" type="checkbox" name="category_'.$row_data['id'].'" id="category_'.$row_data['id'].'" value="'.$row_data['id'].'" '.$checkedString.' /><label class="form-check-label">'.$row_data['name']."</label>";

					if($childcount > 0)
					$str.= self::get_category_child_for_website($row_data['id'], "", $categoryArray);

					$str.= '</div>';
			}

			return $str;
	}






	static function get_built_in_iab_child_list($pid)
	{
		$db      = DAL::get_instance();
		$pid_iab = $db->read_single_column("SELECT iab_id FROM ".TABLE_PREFIX."categories WHERE id=?",array($pid));
		$str = "";
		if($pid_iab != "")
		$str.="'".$pid_iab."'";
		$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."categories WHERE pid=? ORDER BY name",array($pid));
		while($row_data=$row->fetch_assoc())
		{
			if($row_data['iab_id'] != "")
			{
				if($str != "")
				$str.=",";
				$str.="'".$row_data['iab_id']."'";
			}
		}
		if($str != "")
		$str = "(".$str.")";
		return $str;
	}
	static function get_custom_category_child_list($pid,$str = "")
	{
		$db  = DAL::get_instance();
		$row = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."categories WHERE pid=? ORDER BY name",array($pid));
		while($row_data = $row->fetch_assoc())
		{
			$childcount = self::get_category_child_count($row_data['id']);
			if($str != "")
			$str.= ",";
			$str.= intval($row_data['id']);
			if($childcount >0)
			$str.= self::get_custom_category_child_list($row_data['id'],$str);
		}
		return $str;
	}

	static function get_category_from_site($sid)
	{
		$db=DAL::get_instance();

		$catid=$db->read_single_column("SELECT catid FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));

		return intval($catid);
	}

	static function get_category_sites_count($catid)
	{
		$db    = DAL::get_instance();

		$count = $db->read_single_column("SELECT count(id) FROM ".TABLE_PREFIX."sites WHERE catid = ? AND status = 1",array($catid));

		return intval($count);
	}

	static function get_category_last_childs($catid)
	{
		$db= DAL::get_instance();
		$row=$db->execute_query("SELECT * FROM ".TABLE_PREFIX."categories WHERE pid=?",array($catid));
		$rowcount=$row->get_num_records();

		$string='';
		if($rowcount ==0)
		$string.=$catid;

		while($row_data=$row->fetch_assoc())
		{
			$childcount=self::get_category_child_count($row_data['id']);

			if($string !='')
			$string.=',';

			if($childcount >0)
			$string.=self::get_category_last_childs($row_data['id']);
			else
			$string.=$row_data['id'];
		}
		return $string;
	}


	static function get_category_child_id_list($catid, $IDArray = array())
	{
			$db       = DAL::get_instance();
			$row      = $db->execute_query("SELECT * FROM ".TABLE_PREFIX."categories WHERE pid=?",array($catid));

			if(count($IDArray) == 0)
			$IDArray[]  = $catid;

			while($row_data = $row->fetch_assoc())
			{
					$IDArray[]  = $row_data['id'];

					$childcount = self::get_category_child_count($row_data['id']);

				  if($childcount > 0)
				  $IDArray = self::get_category_child_id_list($row_data['id'], $IDArray);
			}

			return $IDArray;
	}

	static function get_parent_category_exclusive($catid)
	{
			$db  			= DAL::get_instance();
			$row 			= $db->execute_query("SELECT * FROM ".TABLE_PREFIX."categories WHERE id = ?",array($catid));
			$rowcount = $row->get_num_records();

			if($rowcount == 0)
			return 0;

			$row_data = $row->fetch_assoc();

			$pid 			= $row_data['pid'];

			if($pid == 0)
			return intval($row_data['exclusive_targeting']);
			else
			return self::get_parent_category_exclusive($pid);
	}


	static function get_last_parent_category_id($catid)
	{
			$db  			= DAL::get_instance();
			$row 			= $db->execute_query("SELECT * FROM ".TABLE_PREFIX."categories WHERE id = ?",array($catid));
			$rowcount = $row->get_num_records();

			if($rowcount == 0)
			return 0;

			$row_data = $row->fetch_assoc();

			$pid 			= $row_data['pid'];

			if($pid == 0)
			return intval($row_data['id']);
			else
			return self::get_last_parent_category_id($pid);
	}









	static function get_site_verification_key($sid)
	{
		$db=DAL::get_instance();

		$verificationKey = $db->read_single_column("SELECT verification_key FROM ".TABLE_PREFIX."sites WHERE id=?",array($sid));

		return $verificationKey;
	}











	static function get_google_pagerank($url)
	{
		$query="http://toolbarqueries.google.com/tbr?client=navclient-auto&ch=".self::CheckHash(self::HashURL($url)). "&features=Rank&q=info:".$url."&num=100&filter=0";
		$data=file_get_contents($query);
		$pos = strpos($data, "Rank_");
		if($pos === false)
		{}
		else
		{
			$pagerank = substr($data, $pos + 9);
			return $pagerank;
		}
	}

	static function StrToNum($Str, $Check, $Magic)
	{
		$Int32Unit = 4294967296; // 2^32
		$length = strlen($Str);
		for($i = 0; $i < $length; $i++)
		{
			$Check *= $Magic;
			if ($Check >= $Int32Unit) {
				$Check = ($Check - $Int32Unit * (int) ($Check / $Int32Unit));
				$Check = ($Check < -2147483648) ? ($Check + $Int32Unit) : $Check;
			}
			$Check += ord($Str[$i]);
		}
		return $Check;
	}

	static function HashURL($String)
	{
		$Check1 = self::StrToNum($String, 0x1505, 0x21);
		$Check2 = self::StrToNum($String, 0, 0x1003F);
		$Check1 >>= 2;
		$Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
		$Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
		$Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF);
		$T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) <<2 ) | ($Check2 & 0xF0F );
		$T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 );
		return ($T1 | $T2);
	}

	static function CheckHash($Hashnum)
	{
		$CheckByte = 0;
		$Flag = 0;
		$HashStr = sprintf('%u', $Hashnum) ;
		$length = strlen($HashStr);
		for ($i = $length - 1; $i >= 0; $i --)
		{
			$Re = $HashStr[$i];
			if (1 === ($Flag % 2))
			{
				$Re += $Re;
				$Re = (int)($Re / 10) + ($Re % 10);
			}
			$CheckByte += $Re;
			$Flag ++;
		}
		$CheckByte %= 10;
		if (0 !== $CheckByte)
		{
			$CheckByte = 10 - $CheckByte;
			if(1 === ($Flag % 2) )
			{
				if(1 === ($CheckByte % 2))
				{
					$CheckByte += 9;
				}
					$CheckByte >>= 1;
			}
		}
		return '7'.$CheckByte.$HashStr;
	}



};

?>
