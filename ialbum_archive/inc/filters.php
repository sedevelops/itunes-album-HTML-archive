<div class="display_options panel">
			
			
				<form method="post">
					
						
					<div class="search_box"><input type="text" name="keywords" id="keywords" value="<?php echo $_POST['keywords']; ?>" /><input type="submit" name="submit" value="search" class="button radius large" /> </div>
					
					<a class="toggle" rel="search_filters" href="#"> Filters</a>
					
					<div id="search_filters" <?php if(isset($_POST['display_options'])){ echo 'style="display:block;"';} ?>>
						
							<table id="year_range_box">
								<tr>
									<td class="label"><label>Year Range</label></td>
									
									<td class="slider">
										<div id="noUiSlider" class="noUiSlider"></div>
									</td>
								</tr>
								
								<input type="hidden" name="display_options[start_year]" id="display_options-start_year" value="<?php echo $start_year; ?>" />
								<input type="hidden" name="display_options[end_year]" id="display_options-end_year" value="<?php echo $end_year; ?>" />
								
							</table>
							
							
							
							
				
						
						<label>Order By</label>
	
						<select name="display_options[orderby]">
							<option <?php if($orderby == 'itunes_album_year') { echo 'selected="selected"';} ?> value="itunes_album_year">Year</option>
							<option <?php if($orderby == 'itunes_artist') { echo 'selected="selected"';} ?> value="itunes_artist">Artist</option>
							<option <?php if($orderby == 'itunes_album') { echo 'selected="selected"';} ?> value="itunes_album">Album</option>
							<option <?php if($orderby == 'brightness') { echo 'selected="selected"';} ?> value="brightness">Cover Lightness</option>
	
						</select>
																	
						
						<label>Order</label>
	
						<select name="display_options[order]">
							<option <?php if($order == 'ASC') { echo 'selected="selected"';} ?> value="ASC">ASC</option>
							
							<option <?php if($order == 'DESC') { echo 'selected="selected"';} ?> value="DESC">DESC</option>
								
						</select>
																	
						<label>&nbsp;</label>
						<input class="button radius small" type="submit" name="submit" value="Filter" />
	
					</div>
					
				</form>
							
	</div>