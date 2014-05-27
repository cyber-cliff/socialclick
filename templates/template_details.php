<?php 
	$short_code = @$_GET['s_c'];
	$Link = new Link($short_code);
	$Details=new StatsUrl($short_code);
?>

<div class="main-item">
  <?php if($Link->getLongUrl()) : ?>
  <div id="url_details_header" class="d_box">
    <div class="track_url">
      <input readonly="readonly" value="<?php echo SITE_URL.$short_code; ?>" onclick="$(this).select()" />
      <span> <b>ctrl+c</b> kalau mo kopi</span> </div>
    <div class="long_url">
      <div>
        <?=$Link->getLongUrl(); ?>
      </div>
    </div>
  </div>
  <?php if(user::loggedin()) : ?>
  <div class="d_box track_url_settings" style="margin-top:5px;">
        <form method="post">
        <div style="float:left;margin:2px auto">
          <div class="stchckbox"> <span title="Only Unique visitor">Only Unique </span>
            <input type="checkbox" name="ouv"  <?php echo $Link->OnlyUniqueVisitor()==true ? "checked":""; ?> />
          </div>
          <div class="stchckbox"> <span title="Hide traffic source">hide referrer </span>
            <input type="checkbox" name="hf" <?php echo $Link->HideReferrer()==true ? "checked":""; ?> />
          </div>
          <input type="hidden" name="shortcode" value="<?=$short_code?>" />
          <div>
            <fieldset style="padding:5px">
            <legend style="border:1px solid #333;border-radius:5px;padding:2px 5px;font-weight:bold;color:#333">
                Filter by country
            </legend>
            <span style="color:#FF1C1C;font-size:12px">*Only selected country can access this link</span>
            <div style="display:block;width:400px;margin:5px;z-index:10">
                <select name="filter_country[]" data-placeholder="Choose a Country..." class="chosen-select" multiple style="width:350px;" >
                    <option value=""></option>
                    <?php 
						$country_list = $Details->getCountryList();
						foreach($country_list as $country){
							if(in_array($country[0],explode(',',$Link->Except_country_list())))
								echo "<option value=".$country[0]." selected=\"selected\">".$country[1]."</option>";
							else
								echo "<option value=".$country[0].">".$country[1]."</option>";
						}
					 ?>
                </select>
            </div>
            <div>
            	<span style="color:#0000FF;font-size:12px;display:block;">*Redirect url if country not on list</span>
                <input name="country_not_on_list" type="text" value="<?= $Link->getConRedirectUrl() ?>" style="color:#333;border: 1px solid #aaa;margin:0px 5px;padding:2px;width:350px"/>
            </div>
            </fieldset>
          </div>
        </div>
        <div style="float:right;display:block">
          <input class="bluebutton" type="button" value="Save" name="save_settings" />
        </div>
        <div style="clear:both"></div>
        </form>
  </div>
  <?php endif ?>
  <div class="d_box" style="margin-top:5px;overflow:hidden;color:#333;font-weight:bolder"> 
  Total Clicks :<span style="color:#06C;display:inline"> <?php echo $Link->TotalClick(); ?> </span>
  </div>
  <div class="d_box" style="margin-top:5px;overflow:hidden;">
    <div><span style="color:#333;font-weight:bolder">Click By Country</span></div>
    <?php 
	if($Link->TotalClick()>0) :
	 $res_bycountry_arry= $Details->ClickByCountry($short_code);
	 foreach($res_bycountry_arry as $key=>$out){
			echo "<div id='clickbycountry' title=\"$out[0]\" >
					<div id='cbcrow'>
						<img src=\"/images/flags/gif/?id=".strtolower($out[1])."\">
						<div class='country_code'>".$out[1]."</div>
						<div>:</div>
						<div>" .$out[2]."</div>
					</div>
				</div>"; 
		}
	else :
		echo "<div style=\"color:#666\">no data</div>";
	endif
	 ?>
    <div id="click_by_contry" style="height:300px;width:500px;margin:0 auto;border:1px solid #DDD"></div>
  </div>
  <div class="d_box" style="margin-top:5px;overflow:hidden;">
    <div>
      <?php if($Link->TotalClick()>0) : ?>
      <div id="platforms_chart" style="height:200px;width:100%"></div>
      <?php else : ?>
      <div><span style="color:#333;font-weight:bolder">Platforms</span></div>
      <div style="color:#666">no data</div>
      <?php endif ?>
    </div>
  </div>
  <?php else : ?>
  <div class="d_box" style="margin-top:5px;overflow:hidden;"> Url Not Found </div>
  <?php endif ?>
</div>
<script type="text/javascript">
      function drawVisualization() {
		  var jsonData = $.ajax({
          url: "/ajax.php?details=CBO",
          dataType:"json",
		  type:'POST',
		  data:{sc:"<?=$short_code?>"},
          async: false
          }).responseText;
		  var data = new google.visualization.DataTable(jsonData,0.6);
			//data.addColumn({id:'',type: 'string', role: 'style'});
		 console.debug(data);
      var view = new google.visualization.DataView(data);
      view.setColumns([0, 1,
                       { calc: "stringify",
                         sourceColumn: 1,
                         type: "string",
                         role: "annotation" },
                       2]);
		var option = {
						title:'Platforms',
						titlePosition:'out',
						titleTextStyle:{color:'#333',bold:true,fontSize:24},
                  		width:"100%", height:200,
						backgroundColor:'#f5f5f5',
				  		bar: {groupWidth: "90%"},
				  		legend: { position: "none" },
					}
        // Create and draw the visualization.
        new google.visualization.BarChart(document.getElementById('platforms_chart')).
            draw(view,option );
      }
      //drawVisualization();
      google.setOnLoadCallback(drawVisualization);
	//GEO MAPS
	google.load('visualization', '1', {'packages': ['geochart']});
     google.setOnLoadCallback(drawRegionsMap);
		
      function drawRegionsMap() {
        var data = google.visualization.arrayToDataTable([
          ['Country', 'Click'],<?php foreach($Details->ClickByCountry($short_code) as $key=>$out){print "\n['".$out[0]."',".$out[2]."],";}?>
        ]);

        var options = {	width:"100%",
					 	height:300,
						backgroundColor:{fill:'#fff',stroke:'#404'},
						datalessRegionColor:'#fff',
						 colorAxis:{minValue: 0,  colors: ['#93FFAE', '#093']}
						};

        var chart = new google.visualization.GeoChart(document.getElementById('click_by_contry'));
        chart.draw(data, options);
    };
   /*
            var data = google.visualization.arrayToDataTable([
              ['Element', 'Click',{ role: 'style' }],
			  <?php foreach($Details->ClickByOs() as $key=>$total) : ?>
			  ['<?= $key ?>',<?= $total ?>, ''],
			  <?php endforeach ?>
            ]);
			*/
	$(".chosen-select").chosen();
</script> 