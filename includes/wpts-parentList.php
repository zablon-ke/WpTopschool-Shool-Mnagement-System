<?php
if (!defined( 'ABSPATH' ) )exit('No Such File');
	  $proversion	=	wpts_check_pro_version();
	  $proclass		=	!$proversion['status'] && isset( $proversion['class'] )? $proversion['class'] : '';
	  $protitle		=	!$proversion['status'] && isset( $proversion['message'] )? $proversion['message']	: '';
	  $prodisable	=	!$proversion['status'] ? 'disabled="disabled"'	: '';
	  $parentFieldList =  array(	'p_fname'		=>	__('First Name', 'wptopschool'),
									'p_mname'		=>	__('Middle Name', 'wptopschool'),
									'p_lname'		=>	__('Last Name', 'wptopschool'),
									's_fname'		=>	__('Student Name', 'wptopschool'),
									'user_email'	=>	__('Parent Email ID', 'wptopschool'),
									'p_edu'			=>	__('Education', 'wptopschool'),
									'p_gender'		=>	__('Gender', 'wptopschool'),
									'p_profession'	=>	__('Profession', 'wptopschool'),
									'p_bloodgrp'	=>	__('Blood Group', 'wptopschool'),
							);
		$sel_classid	=	isset( $_POST['ClassID'] ) ? sanitize_text_field($_POST['ClassID']) : '';
		$class_table	=	$wpdb->prefix."wpts_class";
		$classQuery		=	"select cid,c_name from $class_table Order By cid ASC";
		// if( $current_user_role=='teacher' ) {
		// 	$cuserId	=	intval($current_user->ID);
		// 	$classQuery	=	"select cid,c_name from $class_table where teacher_id=$cuserId";
		// 	$msg		=	'Please Ask Principal To Assign Class';
		// }
	$sel_class		=	$wpdb->get_results( $classQuery );
	global $current_user;
	$role		=	 $current_user->roles;
	$cuserId	=	 $current_user->ID;
?>
<!-- This file form is used for ParentList -->
<div class="wpts-card">
	<div class="wpts-card-head">
		<div class="subject-inner wpts-left wpts-class-filter">
			<form name="ClassForm" id="ClassForm" method="post" action="">
				<label class="wpts-labelMain"><?php _e( 'Select Class Name', 'wptopschool' );?></label>
				<select name="ClassID" id="ClassID" class="wpts-form-control">

					<option value="all" <?php if($sel_classid=='all') echo esc_html("selected","wptopschool"); ?>><?php echo esc_html("All","wptopschool");?></option>

					<?php
					foreach( $sel_class as $classes ) { ?>

						<option value="<?php echo esc_attr($classes->cid);?>" <?php if($sel_classid==$classes->cid) echo esc_html("selected","wptopschool"); ?>>
							<?php echo esc_html($classes->c_name);?>
						</option>
					<?php }
					//if($current_user_role=='administrator' ) { ?>
					<!-- 	<option value="all" <?php if($sel_classid=='all') echo esc_html("selected","wptopschool"); ?>>All</option> -->
					<?php //} ?>
				</select>
			</form>
		</div>
		<div class="wpts-right wpts-import-export">
					<div class="wpts-btn-lists"  title="<?php echo esc_attr($protitle);?>" <?php echo esc_html($prodisable);?>>
						<?php if ( in_array( 'teacher', $role ) ) {?>
                        <div class="wpts-btn-list"  <?php if($proversion['status'] != "1") {?> wpts-tooltip="<?php echo esc_attr($protitle);?>" <?php } ?>>
						<div class="wpts-button-group wpts-dropdownmain wpts-left">
							<button type="button" class="wpts-btn wpts-btn-success  print" id="PrintParent" <?php echo esc_html($prodisable);?> title="<?php //echo esc_attr($protitle);?>">
							<i class="fa fa-print"></i> <?php echo esc_html("Print","wptopschool");?></button>
							<button type="button" class="wpts-btn wpts-btn-success wpts-dropdown-toggle" <?php echo esc_html($prodisable);?> title="<?php //echo esc_attr($protitle);?>"><!--
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span> -->
							</button>
							<div class="wpts-dropdown wpts-dropdown-md">
								<ul>
								<form id="ParentColumnForm" name="ParentColumnForm">
									<li class="wpts-drop-title wpts-checkList"> <?php echo esc_html("Select Columns to Print","wptopschool");?></li>
										<?php foreach( $parentFieldList as $key=>$value ) { ?>
										<li class="wpts-checkList">
											<input type="checkbox" name="ParentColumn[]" value="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" checked="checked">
											<label class="wpts-label" for="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></label>
										</li>
										<?php } ?>
                                <?php $currentSelectClass =	isset($_POST['ClassID']) ? intval($_POST['ClassID']) : '0'; ?>
                                <input type="hidden" name="ClassID" value="<?php  echo esc_attr($currentSelectClass); ?>">
								</form>
								</ul>
							</div>
						</div>
					</div>
						<div class="wpts-btn-list"  <?php if($proversion['status'] != "1") {?> wpts-tooltip="<?php echo esc_attr($protitle);?>" <?php } ?>>
						<div class="psp-dropdownmain wpts-button-group">
							<button type="button" class="wpts-btn print" id="ExportParents" <?php echo esc_html($prodisable);?> title="<?php echo esc_attr($protitle);?>"><i class="fa fa-download"></i> <?php echo esc_html("Export","wptopschool");?> </button>
							<button type="button" class="wpts-btn wpts-btn-blue wpts-dropdown-toggle" <?php echo esc_html($prodisable);?> title="<?php echo esc_attr($protitle);?>">
								<!-- <span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span> -->
							</button>
							<div id="exportcontainer" style="display:none;"></div>
							<div class="wpts-dropdown wpts-dropdown-md wpts-dropdown-right">
								<ul>
									<form id="ExportColumnForm" name="ExportParentColumn" method="POST">
										<li class="wpts-drop-title wpts-checkList"> <?php echo esc_html("Select Columns to Export","wptopschool");?></li>
										<?php foreach( $parentFieldList as $key=>$value ) { ?>
										<li class="wpts-checkList" >
											<input type="checkbox" name="ParentColumn[]" value="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" checked="checked">
											<label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></label>
										</li>
										<?php } ?>
									<?php $currentSelectClass =	isset($_POST['ClassID']) ? intval($_POST['ClassID']) : '0'; ?>
                                    <input type="hidden" name="ClassID" value="<?php echo esc_attr($currentSelectClass); ?>">
										<input type="hidden" name="exportparent" value="exportparent">
									</form>
								</ul>
						</div>
					</div>
				</div>
				<?php } ?>
                        <?php if ( in_array( 'administrator', $role ) ) {?>
						<div class="wpts-btn-list"  <?php if($proversion['status'] != "1") {?> wpts-tooltip="<?php echo esc_attr($protitle);?>" <?php } ?>>
						<div class="wpts-button-group wpts-dropdownmain wpts-left">
							<button type="button" class="wpts-btn wpts-btn-success  print" id="PrintParent" <?php echo esc_html($prodisable);?> title="<?php //echo $protitle;?>">
							<i class="fa fa-print"></i> <?php echo esc_html("Print","wptopschool");?> </button>
							<button type="button" class="wpts-btn wpts-btn-success wpts-dropdown-toggle" <?php echo esc_html($prodisable);?> title="<?php //echo $protitle;?>"><!--
								<span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span> -->
							</button>
							<div class="wpts-dropdown wpts-dropdown-md">
								<ul>
								<form id="ParentColumnForm" name="ParentColumnForm">
									<li class="wpts-drop-title wpts-checkList"> <?php echo esc_html("Select Columns to Print","wptopschool");?></li>
										<?php foreach( $parentFieldList as $key=>$value ) { ?>
										<li class="wpts-checkList">
											<input type="checkbox" name="ParentColumn[]" value="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" checked="checked">
											<label class="wpts-label" for="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></label>
										</li>
										<?php } ?>
                            <?php $currentSelectClass =	isset($_POST['ClassID']) ? intval($_POST['ClassID']) : '0'; ?>
							<input type="hidden" name="ClassID" value="<?php echo esc_attr($currentSelectClass); ?>">

								</form>
								</ul>
							</div>
						</div>
					</div>
					<div class="wpts-btn-list"  <?php if($proversion['status'] != "1") {?> wpts-tooltip="<?php echo esc_attr($protitle);?>" <?php } ?>>
						<div class="psp-dropdownmain wpts-button-group">
							<button type="button" class="wpts-btn print" id="ExportParents" <?php echo $prodisable;?> title="<?php echo esc_attr($protitle);?>"><i class="fa fa-download"></i> <?php echo esc_html("Export","wptopschool");?> </button>
							<button type="button" class="wpts-btn wpts-btn-blue wpts-dropdown-toggle" <?php echo esc_html($prodisable);?> title="<?php echo esc_attr($protitle);?>">
								<!-- <span class="caret"></span>
								<span class="sr-only">Toggle Dropdown</span> -->
							</button>
							<div id="exportcontainer" style="display:none;"></div>
							<div class="wpts-dropdown wpts-dropdown-md wpts-dropdown-right">
								<ul>
									<form id="ExportColumnForm" name="ExportParentColumn" method="POST">
										<li class="wpts-drop-title wpts-checkList"> <?php echo esc_html("Select Columns to Export","wptopschool");?> </li>
										<?php foreach( $parentFieldList as $key=>$value ) { ?>
										<li class="wpts-checkList" >
											<input type="checkbox" name="ParentColumn[]" value="<?php echo esc_attr($key); ?>" id="<?php echo esc_attr($key); ?>" checked="checked">
											<label for="<?php echo esc_attr($key); ?>"><?php echo esc_html($value); ?></label>
										</li>
										<?php } ?>
										<input type="hidden" name="ClassID" value="<?php if(isset($_POST['ClassID'])) echo esc_attr(sanitize_text_field($_POST['ClassID'])); else echo 0; ?>">
										<input type="hidden" name="exportparent" value="exportparent">
									</form>
								</ul>
						</div>
					</div>
				</div>
					<?php }?>
			</div>
		</div>
	</div>
	<div class="wpts-card-body">
			<?php if( empty( $sel_class ) && $current_user_role=='teacher' ) {
				echo '<div class="alert alert-danger wpts-col-lg-2">'.esc_html($msg).'</div>';
			} else { ?>
			<div class="wpts-row">
			<div class="wpts-col-md-12 table-responsive">
			<table id="parent_table" class="wpts-table" cellspacing="0" width="100%" style="width:100%">
			<thead>
				<tr>
					<th><?php echo apply_filters( 'wpts_parent_name_list_detail', esc_html__( 'Parent Name', 'wptopschool' )); ?></th>
					<th><?php echo esc_html("Student Name","wptopschool");?></th>
					<th><?php echo apply_filters( 'wpts_parent_email_list_detail', esc_html__( 'Parent Email ID', 'wptopschool' )); ?></th>
					<th  align="center" class="nosort"><?php echo esc_html("Action","wptopschool");?></th>
				</tr>
			</thead>
			<tbody>
				<?php
				$student_table	=	$wpdb->prefix."wpts_student";
				$users_table	=	$wpdb->prefix."users";
				if(isset($_POST['ClassID'])){
					$class_id=intval($_POST['ClassID']);
				} else if(!empty($sel_class)) {
					$class_id = 'all';
				} else {
					$class_id='';
				}
				$classquery	=	" AND class_id='".esc_sql($class_id)."' ";
				if($class_id=='all'){
					$classquery	=	"";
				}
				$parent_ids=$wpdb->get_results("select DISTINCT u.user_email, CONCAT_WS(' ', p_fname, p_mname, p_lname ) AS full_name, p.s_fname,p.s_lname, p.wp_usr_id, p.parent_wp_usr_id from $student_table p, $users_table u where u.ID=p.parent_wp_usr_id AND user_login != 'parent' $classquery");

				foreach($parent_ids as $pinfo)
				{
				$parent_ids
				?>
					<tr>
						<td><?php echo esc_html($pinfo->full_name);?></td>
						<td><?php echo esc_html($pinfo->s_fname." ".$pinfo->s_lname); ?> </td>
						<td><?php echo esc_html($pinfo->user_email);?></td>
						<td align="center">
							<div class="wpts-action-col">
								<a href="javascript:void(0)" title="View" data-pop="ViewModal" data-id="<?php echo esc_attr(intval($pinfo->parent_wp_usr_id));?>" class="ViewParent wpts-popclick">
								<i class="icon dashicons dashicons-visibility wpts-view-icon"></i></a>
								<a href="<?php echo esc_url(wpts_admin_url().'sch-student&id='.esc_attr(intval($pinfo->wp_usr_id)).'&edit=true#parent-field-lists');?>" title="Edit"><i class="icon dashicons dashicons-edit wpts-edit-icon"></i></a>
							</div>
						</td>
					</tr>
				<?php
				}
				?>
			</tbody>
			<tfoot>
			  <tr>
				<th><?php echo apply_filters( 'wpts_parent_name_list_detail', esc_html__( 'Parent Name', 'wptopschool' )); ?></th>
				<th><?php echo esc_html("Student Name","wptopschool");?></th>
				<th><?php echo apply_filters( 'wpts_parent_email_list_detail', esc_html__( 'Parent Email ID', 'wptopschool' )); ?></th>
				<th  align="center"><?php echo esc_html("Action","wptopschool");?></th>
			  </tr>
			</tfoot>
		  </table>
		</div>
		</div>
		 <?php } ?>
	</div>
</div>