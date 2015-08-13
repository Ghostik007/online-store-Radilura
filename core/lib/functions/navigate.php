<?
function navigate($page, $numpages)
{
	$out = '';
	
	$numb_nav_a = '<td align="center"><div class = "numb_nav_a"';
	$numb_nav_na = '<td align="center"><div class = "numb_nav_na"';
	
if($numpages>1){
		$navigate = '';
		if($page>1){
			$startpage = $numb_nav_a.' id = "startpage"><<</div></td>';
		}else{
			$startpage = $numb_nav_na.' id = "startpages"><<</div></td>';
		}

		if($page < ($numpages)){
			$endpage = $numb_nav_a.' id = "endpage">>></div></td>';
		}else{
			$endpage = $numb_nav_na.' id = "endpages">>></div></td>';
		}
		
		if($page-4>0){
			$page4left = $numb_nav_a.' id = "page4left">'.($page - 4).'</div></td>';
		}else{
			$page4left = '';
		}
		
		if($page-3>0){
			$page3left = $numb_nav_a.' id = "page3left">'.($page - 3).'</div></td>';
		}else{
			$page3left = '';
		}		
		
		if($page-2>0){
			$page2left = $numb_nav_a.' id = "page2left">'.($page - 2).'</div></td>';
		}else{
			$page2left = '';
		}		
		
		if($page-1>0){
			$page1left = $numb_nav_a.' id = "page1left">'.($page - 1).'</div></td>';
		}else{
			$page1left = '';
		}	
		
		if($page+1<=$numpages){
			$page1right = $numb_nav_a.' id = "page1right">'.($page + 1).'</div></td>';
		}else{
			$page1right = '';
		}
		
		if($page+2<=$numpages){
			$page2right = $numb_nav_a.' id = "page2right">'.($page + 2).'</div></td>';
		}else{
			$page2right = '';
		}
		
		if($page+3<=$numpages){
			$page3right = $numb_nav_a.' id = "page3right">'.($page + 3).'</div></td>';
		}else{
			$page3right = '';
		}	
		
		if($page + 4 <= $numpages){
			$page4right = $numb_nav_a.' id = "page4right">'.($page + 4).'</div></td>';
		}else{
			$page4right = '';
		}

		if($page == '1'){
			$navigate .= '<table class = "navigate_panel"  cellspacing="0" cellpadding="5"><tr>'.$startpage.'<td align="center"><strong>'.$page.'</strong></font></td>'.$page1right.$page2right.$page3right.$page4right.$endpage.'</tr></table>';
		}elseif($page == '2'){
			$navigate .= '<table class = "navigate_panel"  cellspacing="0" cellpadding="5"><tr>'.$startpage.$page1left.'<td align="center"><strong>'.$page.'</strong></font></td>'.$page1right.$page2right.$page3right.$endpage.'</tr></table>';
		}elseif($numpages-$page=='0'){
			$navigate .= '<table class = "navigate_panel"  cellspacing="0" cellpadding="5"><tr>'.$startpage.$page4left.$page3left.$page2left.$page1left.'<td align="center"><strong>'.$page.'</strong></font></td>'.$endpage.'</tr></table>';		
		}elseif($numpages-$page=='1'){
			$navigate .= '<table class = "navigate_panel"  cellspacing="0" cellpadding="5"><tr>'.$startpage.$page3left.$page2left.$page1left.'<td align="center"><strong>'.$page.'</strong></font></td>'.$page1right.$endpage.'</tr></table>';
		}else{
			$navigate .= '<table class = "navigate_panel"  cellspacing="0" cellpadding="5"><tr>'.$startpage.$page2left.$page1left.'<td align="center"><strong>'.$page.'</strong></font></td>'.$page1right.$page2right.$endpage.'</tr></table>';
		}	
	}
	
	if(isset($navigate)){
		$out = '<div style="border: 0px solid #000; width: 200px; position: relative; padding: 5px; background-color: #ffffff; border-radius: 10px;" >'.$navigate.'</div>';
	}
	
	return $out;
}