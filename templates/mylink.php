<?php 
global $shorturl,$user,$currentp,$total_records,$lp;
?>
<div style="padding:5px;">
<div id="addurl">
	<div style="color:#333">
    	<b>Paste your url here..</b>
    </div>
    <div>
    	<input type="text" name="theurl" id="theurl"  width="200px"/>
        <div style="display:inline-block;">
        	<button class="bluebutton" type="button" id="addlink" style="font-size:12px;padding:5px;border-radius:0;cursor:pointer">Add Url</button>
        </div>
    </div>
</div>
<div id="listurl" style="margin-top:5px;min-height:338px;">
	<div class="url_t_m">My Link</div>
    <div id="listoflink">
    	<table border="0" class="link_t" cellpadding="0" cellspacing="0">
        	<tr>
            	<td width="200px">Long url</td>
                <td width="150px">Track url</td>
                <td width="100px">Created</td>
                <td width="150px">Title</td>
                <td width="50px">Click</td>
                <td width="50px">Action</td>
            </tr>
           <!-- <tr>
            	<td><a href="http://www.google.com.my">http://www.google.com.my</a></td>
            	<td><a href="http://domain.com/axcks" rel="noreferrer">http://domain.com/axcks</a></td>
                <td>Today</td>
                <td><div><span title="google">Google</span></div></td>
                <td><a href="#">Details</a></td>
            </tr> -->
            <?php 
			if($user->loggedin()){
			#var_dump(number_only($_REQUEST['lp']));
			$currentp = 0;
			$perpage = 10;
			$total_user_url = $shorturl->totalLink(getUserID());
			$lp = 1;
			//$total_records = null;
			if(isset($_REQUEST['lp'])){//link page
				$lp = number_only($_REQUEST['lp']);
				if($lp==null){$currentp = 0;$lp=1;}
				else $currentp = ($lp-1)*$perpage;
			}
			$totalpg = ceil($total_user_url/$perpage) ;
			
			$row  = $shorturl->loadList(getUserID(),$currentp,$perpage);
			$total_records = count($row);

			if($row!=null){
            foreach($row as $dt){ 
				$short_code = $dt[2];
				$long_url = $dt[1];
				$date_tz = new DateTime($dt[3],new DateTimeZone(ini_get('date.timezone')));
				$time_elapsed = datetime_elapsed( date(DATE_RFC2822,$date_tz->format('U')) );
				$date_url_long = date("d M Y h:i:s A O",$date_tz->format('U'));
			?>
			<tr class="listlink">
            	<td>
                	<a href="<?php echo $dt[1] ?>" title="<?php echo $long_url ?>" rel="noreferrer">
						<?php 
							$itulink = strlen($long_url)>32 ? substr($dt[1],0,32)."..." : $long_url; 
							echo str_replace(array('http://','https://'),"",$itulink);		
						?>
                    </a>
                </td>
            	<td>
                	<a href="<?php echo SITE_URL.$short_code ?>" rel="noreferrer" rel="nofollow" target="_blank">
                    	<?php echo SITE_URL.$short_code ?>
                    </a>               	
                </td>
                <td><span title="<?php echo $date_url_long ?>"><?php echo $time_elapsed; ?></span></td>
                <td>
                	<div>
                    	<span title="<?php echo $dt[4] ?>" style="display:block;overflow:hidden">
						<?php echo strlen($dt[4])>15 ? substr($dt[4],0,15)."..." : $dt[4] ?>
                        </span>
                    </div>
                </td>
                <td align="center"><?php echo $dt[5] ?></td>
                <td align="center"><a href="/<?php echo $short_code ?>+" id="<?php echo trim($short_code) ?>">Details</a></td>
            </tr>
			<?php }}} ?>
        </table>
    </div>
</div>
<?php 
function pnext(){
	global $lp;
	echo $lp+1;
}
function pprev(){
	global $lp;
	echo $lp-1;
}

function list_of_link(){	
	global $currentp,$total_records;
	$st = $currentp+1;
	$ntah =$currentp + $total_records;
	//var_dump($row);
	echo $st."-".$ntah;
}

?>
<?php if($user->loggedin()){ ?>
<div id="lpn" style="margin-top:5px;">
<span><?php  ?></span>
<span><?php list_of_link(); echo " of ".$total_user_url ?></span>
<?php 
	//echo "<div style=\"display:inline;padding:1px\"><a style=\"text-decoration:none;\" href=\"mylink.php\"><<</a></div>";
if(@$totalpg>1){
?>
	<div class="pnv">
    <?php if($currentp==0){ ?>
    	<a class="lppage">&lt;&lt;</a>
    <?php }else{ ?>
    	<a href="?lp=<?php pprev(); ?>">&lt;&lt;</a>
    <?php } ?>
    </div>
    <div class="pnv"><?php echo $lp; ?></div>	
    <div class="pnv">
    	<?php if($totalpg<=$lp){ ?>
    	<a class="lppage" >&gt;&gt;</a>
    	<?php }else{ ?>
        <a href="?lp=<?php pnext() ?>">&gt;&gt;</a>
        <?php } ?>
    </div>
<?php }?>
</div>
<?php }?>
</div>