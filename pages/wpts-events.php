<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
wpts_header(); ?>
<?php
	if( is_user_logged_in() ) {
		global $current_user, $wpdb;
		$current_user_role=$current_user->roles[0];
		wpts_topbar();
		wpts_sidebar();
		wpts_body_start();
		if($current_user_role=='administrator' || $current_user_role=='teacher')
		{
		?>
		<div class="wpts-card">
		<div class="wpts-card-head">
            <h3 class="wpts-card-title"><?php echo apply_filters( 'wpts_event_heading_item',esc_html("Event calendar","wptopschool")); ?></h3>
        </div>
         <div class="wpts-card-body">
			<div id="calendar"></div>
		<div class="wpts-popupMain" id="eventPop">
		  <div class="wpts-overlayer"></div>
		  <div class="wpts-popBody">
		    <div class="wpts-popInner">
		    	<a href="javascript:;" class="wpts-closePopup"></a>
		    	<div class="wpts-popup-body">
		    		<div class="wpts-panel-heading">
						<h3 class="wpts-panel-title"><?php echo apply_filters( 'wpts_add_event_popup_heading_item',esc_html("Add Event","wptopschool")); ?></h3>
					</div>
		    		<div class="wpts-popup-cont">
		    		<div id="response"></div>

						<form name="calevent_entry" method="post" class="form-horizontal" id="calevent_entry">
								<?php  do_action('wpts_before_event'); ?>
							<div class="wpts-col-sm-6 wpts-col-xs-12">
								<div class="wpts-form-group">
									<label class="wpts-label"><?php esc_html_e("Start Date","wptopschool");?><span class="wpts-required">*</span></label>
									<input type="hidden"  id="wpts_locationginal" value="<?php echo admin_url();?>"/>
									<input type="text" name="sdate" class="wpts-form-control sdate" id="sdate">
								</div>
							</div>
							<div class="wpts-col-sm-6">
								<div class="wpts-form-group">
									<label class="wpts-label"><?php esc_html_e("Start Time","wptopschool"); ?> <span class="wpts-required">*</span></label>
									<input type="text" name="stime" class="wpts-form-control stime" id="stime">
								</div>
							</div>
							<div class="wpts-col-sm-6">
								<div class="wpts-form-group">
									<label class="wpts-label"><?php esc_html_e("End Date","wptopschool");?><span class="wpts-required">*</span></label>
									<input type="text" name="edate" class="wpts-form-control edate" id="edate">
								</div>
							</div>

							<div class="wpts-col-sm-6">
								<div class="wpts-form-group">
									<label class="wpts-label"><?php esc_html_e("End Time","wptopschool"); ?><span class="wpts-required">*</span></label>
									<input type="text" name="etime" class="wpts-form-control etime" id="etime">
								</div>
							</div>
							<div class="wpts-col-sm-12">
								<div class="wpts-form-group">
									<label class="wpts-label"><?php esc_html_e("Title","wptopschool"); ?> *</label>
									<input type="text" name="evtitle" class="wpts-form-control" id="evtitle">
								</div>
							</div>
							<div class="wpts-col-sm-12">
								<div class="wpts-form-group">
									<label class="wpts-label"><?php esc_html_e("Description","wptopschool"); ?></label>
									<textarea name="evdesc" class="wpts-form-control" id="evdesc"></textarea>
								</div>
							</div>
							<div class="wpts-col-sm-6">
								<div class="wpts-form-group">
								<label class="wpts-label"><?php esc_html_e("Type","wptopschool"); ?></label>
									<select class="wpts-form-control" id="evtype" name="evtype">
										<option value="0"><?php esc_html_e( 'External(Show to all)', 'wptopschool' )?></option>
										<option value="1"><?php esc_html_e( 'Internal(Show to teachers only)', 'wptopschool' )?></option>
									</select>
									<input type="hidden" name="evid" class="wpts-form-control" id="evid">
								</div>
							</div>
							<div class="wpts-col-sm-6">
								<div class="wpts-form-group">
									<label class="wpts-label"><?php esc_html_e("Color","wptopschool"); ?></label>
									<select name="evcolor" class="wpts-form-control" id="evcolor">
										<option class="bg-blue" value=""><?php esc_html_e( 'Default', 'wptopschool' );?></option>
									</select>
								</div>
							</div>
							<?php  do_action('wpts_after_event'); ?>
						</form>
						<div class="wpts-col-sm-12">
							<button type="button" id="calevent_save" class="wpts-btn wpts-btn-success"><?php esc_html_e("Save","wptopschool"); ?></button>
						</div>
						</div>
					</div>
					</div>
				</div>
		    </div>
		     <!-- popup -->
		    <div class="wpts-popupMain" id="editeventPop">
			  <div class="wpts-overlayer"></div>
			  <div class="wpts-popBody">
			    <div class="wpts-popInner">
			    		<a href="javascript:;" class="wpts-closePopup"></a>
						<div class="wpts-popup-body">
			    		<div class="wpts-panel-heading">
							<h3 class="wpts-panel-title" id="viewEventTitle"></h3>
						</div>
			    		<div class="wpts-popup-cont">
			    		<div class="wpts-col-md-6">
			    			<div class="wpts-form-group">
								<label class="wpts-labelMain"><?php echo apply_filters('wpts_add_event_popup_start_label',esc_html("Start :","wptopschool"));; ?></label> <span id="eventStart"> </span>
							</div>
						</div>
						<div class="wpts-col-md-6">
							<div class="wpts-form-group">
								<label class="wpts-labelMain"><?php echo apply_filters('wpts_add_event_popup_end_label',esc_html("End :","wptopschool"));; ?></label> <span id="eventEnd"> </span>
							</div>
						</div>
						<div class="wpts-col-md-12">
							<div class="wpts-form-group">
							<label><?php echo apply_filters('wpts_add_event_popup_description_label',esc_html("Description :","wptopschool")); ?> </label> <span id="eventDesc"> </span>
							</div>
						</div>
						<?php if($current_user_role=='administrator'){?>
						<div class="wpts-col-md-12">
							<button class="wpts-btn wpts-btn-success" id="editEvent"><?php echo apply_filters('wpts_add_event_popup_button_edit_text',esc_html("Edit Event","wptopschool")); ?></button>
							<button class="wpts-btn wpts-btn-danger" id="deleteEvent"><?php echo apply_filters('wpts_add_event_popup_button_delete_text',esc_html("Delete","wptopschool")); ?></button>
						</div>
					<?php }?>
			    	</div>
			    </div>
			</div>
			</div>
		</div>
		     <!-- popup-end -->
		  </div>
		</div>

		<?php  }else if($current_user_role=='parent' || $current_user_role='student'){ ?>
		<div class="wpts-card">
			<div class="wpts-card-head">
        		<h3 class="wpts-card-title"><?php echo apply_filters( 'wpts_event_heading_item',esc_html("Event calendar","wptopschool")); ?></h3>
    		</div>
			<div class="wpts-card-body">
				<div id="calendar"></div>
		<div class="wpts-popupMain" id="editeventPop">
		  <div class="wpts-overlayer"></div>
		  <div class="wpts-popBody">
		    <div class="wpts-popInner">
		    		<a href="javascript:;" class="wpts-closePopup"></a>
		    		<div class="wpts-popBody">
		    		<div class="wpts-panel-heading">
						<h3 class="wpts-panel-title" id="viewEventTitle"></h3>
					</div>
		    			<div class="wpts-popup-cont">
		    			<div class="col-md-6">
		    				<div class="wpts-form-group">

								<label class="wpts-labelMain"><?php echo apply_filters('wpts_add_event_popup_start_label',esc_html("Start :","wptopschool"));; ?></label> <span id="eventStart"> </span>
							</div>
						</div>
							<div class="col-md-6">
								<div class="wpts-form-group">
									<label class="wpts-labelMain"><?php echo apply_filters('wpts_add_event_popup_end_label',esc_html("End :","wptopschool"));; ?></label> <span id="eventEnd"> </span>
								</div>
							</div>
							<div class="col-md-12">
								<div class="wpts-form-group">
									<label>	<label><?php echo apply_filters('wpts_add_event_popup_description_label',esc_html_e("Description :","wptopschool")); ?> </label> <span id="eventDesc"> </span></label> <span id="eventDesc"> </span>
								</div>
							</div>
		    	</div>
		    </div>
		</div>
		</div>
		</div>
		</div>
		</div>
	<?php }
		wpts_body_end();
		wpts_footer();
	}
	else{
		//Include Login Section
		include_once( WPTS_PLUGIN_PATH .'/includes/wpts-login.php');
	}
?>
