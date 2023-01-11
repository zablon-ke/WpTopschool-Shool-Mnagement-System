<!-- Start Import Data Remove Popup -->

<div class="wpts-popupMain wpts-popVisible" id="ImportRemove" style="display:none;">

	<div class="wpts-overlayer"></div>

	<div class="wpts-popBody wpts-alert-body">

		<div class="wpts-popInner">

			<a href="javascript:;" class="wpts-closePopup"></a>

			<div class="wpts-popup-cont wpts-alertbox wpts-alert-success">

				<div class="wpts-alert-icon-box">

					<!-- <i class="icon wpts-icon-tick-mark"></i> -->

					<i class="icon dashicons dashicons-yes"></i>

				</div>

				<div class="wpts-alert-data">

					<input type="hidden" name="teacherid" id="teacherid">

					<h4><?php echo esc_html("Success","wptopschool");?></h4>

					<p><?php echo esc_html("Data Deleted Successfully.","wptopschool");?></p>

				</div>


			</div>

		</div>

	</div>

</div>


<!-- End Import data remove Popup -->

<!-- Start Data Success Popup -->


<div class="wpts-popupMain wpts-popVisible" id="SuccessModal" style="display:none;">

	<div class="wpts-overlayer"></div>

	<div class="wpts-popBody wpts-alert-body">

		<div class="wpts-popInner">

			<a href="javascript:;" class="wpts-closePopup"></a>

			<div class="wpts-popup-cont wpts-alertbox wpts-alert-success">

				<div class="wpts-alert-icon-box">

					<!-- <i class="icon wpts-icon-tick-mark"></i> -->

					<i class="icon dashicons dashicons-yes"></i>

				</div>

				<div class="wpts-alert-data">

					<input type="hidden" name="teacherid" id="teacherid">

					<h4><?php echo esc_html("Success","wptopschool");?></h4>

					<p><?php echo esc_html("Data Saved Successfully.","wptopschool");?></p>

				</div>

			</div>

		</div>

	</div>

</div>


<!-- End Data Save Popup -->


<!-- Start Data Saving Popup -->


<div class="wpts-preLoading-onsubmit" id="SavingModal" style="display:none;">

   <div class="wpts-loading_shape-onsubmit">

     <a href="javascript:;" class="wpts-closeLoading"></a>

     <div class="wpts-loader-onsubmit"></div>

     <p class="wpts-saving-text"><?php echo esc_html("Saving data...","wptopschool");?></p>

   </div>

</div>


<!-- End Data Saving Popup -->

<!-- Start Data Warning Popup -->

<div class="wpts-popupMain wpts-popVisible" id="WarningModal" data-pop="WarningModal" style="display:none;">

  <div class="wpts-overlayer"></div>

  <div class="wpts-popBody wpts-alert-body">

	<div class="wpts-popInner">

		<a href="javascript:;" class="wpts-closePopup"></a>

		<div class="wpts-popup-cont wpts-alertbox wpts-alert-warning">

			<div class="wpts-alert-icon-box">

				<i class="icon wpts-icon-question-mark"></i>

			</div>

			<div class="wpts-alert-data">

				<h4><?php echo esc_html("Warning","wptopschool");?></h4>

				<p class="wpts-popup-return-data"><?php echo esc_html("Something went wrong!","wptopschool");?></p>

			</div>

			<div class="wpts-alert-btn">

				<button type="submit" class="wpts-btn wpts-dark-btn wpts-popup-cancel"><?php echo esc_html("Cancel","wptopschool");?></button>

			</div>

		</div>

	</div>

  </div>

</div>

<!-- End Data Warning Popup -->

<!-- Start Data Delete Popup -->

<div class="wpts-popupMain wpts-popVisible" id="DeleteModal" data-pop="DeleteModal" style="display:none;">

  <div class="wpts-overlayer"></div>

  <div class="wpts-popBody wpts-alert-body">

	<div class="wpts-popInner">

		<a href="javascript:;" class="wpts-closePopup"></a>

		<div class="wpts-popup-cont wpts-alertbox wpts-alert-danger">

			<div class="wpts-alert-icon-box">

				<i class="icon wpts-icon-question-mark"></i>

			</div>

			<div class="wpts-alert-data">

				<h4><?php echo esc_html("Confirmation Needed","wptopschool");?></h4>

				<p><?php echo esc_html("Are you sure want to delete?","wptopschool");?></p>

			</div>

			<div class="wpts-alert-btn">

				<input type="hidden" name="teacherid" id="teacherid">

				<a class="wpts-btn wpts-btn-danger ClassDeleteBt"><?php echo esc_html("Ok","wptopschool");?></a>

				<a href="javascript:;" class="wpts-btn wpts-dark-btn wpts-popup-cancel"><?php echo esc_html("Cancel","wptopschool");?></a>

			</div>

		</div>

	</div>

  </div>

</div>


<!-- End Data Delete Popup -->