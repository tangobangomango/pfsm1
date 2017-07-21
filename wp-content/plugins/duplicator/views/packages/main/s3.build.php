<?php
	$Package = DUP_Package::getActive();
	$ajax_nonce	= wp_create_nonce('dup_package_build');

    // Allows for auto cycling of message in the future - just add dp_texts[n] and dp_contents[n]
    $dp_texts = array();
    $dp_texts[0] = __('Get even more power & features with', 'duplicator');
    $dp_contents[]  = array();
    $dp_contents[0] = 'package_build_more_power';
    $dp_index = rand(0, count($dp_contents) - 1);
    $dp_text = $dp_texts[$dp_index];
    $dp_content = $dp_contents[$dp_index];
    $dp_url = "https://snapcreek.com/duplicator/?utm_source=duplicator_free&utm_medium=wordpress_plugin&utm_content={$dp_content}&utm_campaign=duplicator_pro";

?>

<style>
	div#dup-progress-area {text-align:center; max-width:800px; min-height:200px;  border:1px solid silver; border-radius:5px; margin:25px auto 10px auto; padding:0px; box-shadow: 0 8px 6px -6px #999;}
	div.dup-progress-title {font-size:22px;padding:5px 0 20px 0; font-weight: bold}
	div#dup-progress-area div.inner {padding:10px; line-height:22px}
	div#dup-progress-area h2.title {background-color:#efefef; margin:0px}
	div#dup-progress-area span.label {font-weight:bold}
	div#dup-msg-success {color:#18592A; padding:5px;}
	div#dup-msg-success fieldset, 
	div#dup-msg-error fieldset {text-align:left; width:95%; border:1px solid #dfdfdf; border-radius:5px;}
	div.dup-msg-error-area {overflow-y: scroll; padding:5px 15px 5px 15px; max-height:150px; max-width: 700px}
	div.dup-msg-success-stats{color:#999;margin:10px 0px 0px 0px}
	div.dup-msg-success-links {margin:20px 5px 5px 5px; font-size: 14px; font-weight: bold}
	div#dup-msg-error {color:maroon; padding:5px;}
	div#dup-progress-area div.done-title {font-size:22px; font-weight:bold; margin:0px 0px 10px 0px}
	div#dup-logs {text-align:center; margin:auto; padding:5px; width:350px;}
	div#dup-logs a {font-size:15px; text-decoration:none !important; display:inline-block; margin:20px 0px 5px 0px}
	div.dup-button-footer {text-align:right; margin:20px 10px 0px 0px}
	button.button {font-size:16px !important; height:30px !important; font-weight:bold; padding:0px 10px 5px 10px !important; min-width: 150px }
	span.dup-btn-size {font-size:11px;font-weight: normal}
	p.get-pro {font-size:12px; color:#777; border-top:1px solid #eeeeee; padding:5px 0 0 0; margin:0; font-style:italic}
</style>

<!-- =========================================
TOOL BAR: STEPS -->
<table id="dup-toolbar">
	<tr valign="top">
		<td style="white-space: nowrap">
			<div id="dup-wiz">
				<div id="dup-wiz-steps">
					<div class="completed-step"><a>1-<?php _e('Setup', 'duplicator'); ?></a></div>
					<div class="completed-step"><a>2-<?php _e('Scan', 'duplicator'); ?> </a></div>
					<div class="active-step"><a>3-<?php _e('Build', 'duplicator'); ?> </a></div>
				</div>
				<div id="dup-wiz-title">
					<?php _e('Step 3: Build Package', 'duplicator'); ?>
				</div> 
			</div>
		</td>
		<td>
			<a href="?page=duplicator" class="add-new-h2"><i class="fa fa-archive"></i> <?php _e("Packages", 'duplicator'); ?></a> &nbsp;
			<span> <?php _e("Create New", 'duplicator'); ?></span>
		</td>
	</tr>
</table>		
<hr class="dup-toolbar-line">


<form id="form-duplicator" method="post" action="?page=duplicator">

	<!--  PROGRESS BAR -->
	<div id="dup-progress-bar-area">
		<div class="dup-progress-title"><i class="fa fa-cog fa-spin"></i> <?php _e('Building Package', 'duplicator'); ?></div>
		<div id="dup-progress-bar"></div>
		<b><?php _e('Please Wait...', 'duplicator'); ?></b><br/><br/>
		<i><?php _e('Keep this window open during the build process.', 'duplicator'); ?></i><br/>
		<i><?php _e('This may take several minutes.', 'duplicator'); ?></i><br/>
	</div>
	
	<div id="dup-progress-area" class="dup-panel" style="display:none">
		<div class="dup-panel-title"><b style="font-size:22px"><?php _e('Build Status', 'duplicator'); ?></b></div>
		<div class="dup-panel-panel">

			<!--  =========================
			SUCCESS MESSAGE -->
			<div id="dup-msg-success" style="display:none">
				<div class="dup-hdr-success">
					<i class="fa fa-check-square-o fa-lg"></i> <?php _e('Package Completed', 'duplicator'); ?>
				</div>
				
				<div class="dup-msg-success-stats">
					<b><?php _e('Name', 'duplicator'); ?>:</b> <span id="data-name-hash"></span><br/>
					<b><?php _e('Process Time', 'duplicator'); ?>:</b> <span id="data-time"></span><br/>
				</div><br/>
				
				<button id="dup-btn-installer" class="button button-primary button-large">
					<i class="fa fa-bolt"></i> <?php _e("Installer", 'duplicator') ?>
					<span id="dup-btn-installer-size" class="dup-btn-size"></span>
				</button> &nbsp;
				<button id="dup-btn-archive" class="button button-primary button-large">
					<i class="fa fa-file-archive-o"></i> <?php _e("Archive", 'duplicator') ?>
					<span id="dup-btn-archive-size" class="dup-btn-size"></span>
				</button>
				<div style='margin: 7px 0 20px 0; color:#999'>
					<small><i><?php _e("click buttons to download", 'duplicator') ?></i></small>
				</div>
				<div class="dup-msg-success-links">
					<?php printf("<a href='?page=duplicator'>[ %s ]</a>", 	__('All Packages', 'duplicator'));?>
					<?php printf("<a href='?page=duplicator&tab=new1'>[ %s ]</a>", 	__('Create New', 'duplicator'));?>
				</div>
				<br/>
				<p class="get-pro">
					<?php echo $dp_text ?>&nbsp;<a target="_blank" href="<?php echo $dp_url;?>"><?php _e('Professional', 'duplicator'); ?></a>!
                </p>
			</div>
			
			<!--  =========================
			ERROR MESSAGE -->
			<div id="dup-msg-error" style="display:none">
				<div class="done-title"><i class="fa fa-chain-broken"></i> <?php _e('Host Build Interrupt', 'duplicator'); ?></div>
				<b><?php _e('This host has generated an exception.', 'duplicator'); ?></b><br/>
			
				<i><?php _e("Please click the 'Try Again' button.", 'duplicator'); ?></i><br/><br/>
				
                <input type="button" class="button" value="<?php _e('Try Again', 'duplicator'); ?>" onclick="window.location = 'admin.php?page=duplicator&tab=new1&retry=1'" />
				<input type="button" style="margin-right:10px;" class="button" value="<?php _e('Diagnose', 'duplicator'); ?>" onclick="window.open('https://snapcreek.com/duplicator/docs/faqs-tech/#faq-trouble-100-q', '_blank');return false;" />
                              
				<fieldset>
					<legend><b><?php _e('Details', 'duplicator'); ?></b></legend>
					<div class="dup-msg-error-area">
						<div id="dup-msg-error-response-time">
							<span class="label"><?php _e("Allowed Runtime:", 'duplicator'); ?></span>
							<span class="data"></span>
						</div>
						<div id="dup-msg-error-response-status">
							<span class="label"><?php _e("Server Status:", 'duplicator'); ?></span>
							<span class="data"></span>
						</div>
						<div id="dup-msg-error-response-text">
							<span class="label"><?php _e("Error Message:", 'duplicator'); ?></span><br/>
							<span class="data"></span>
						</div>
					</div>
				</fieldset><br/>
				
				<fieldset style="color:#777">
					<legend><b> <?php _e('Notice', 'duplicator'); ?></b></legend>
					<div class="dup-msg-error-area">
						<?php printf('<b><i class="fa fa-folder-o"></i> %s %s</b> <br/> %s',
							__('Build Folder:'),
								DUPLICATOR_SSDIR_PATH_TMP,
							__("Some servers close connections quickly; yet the build can continue to run in the background. To validate if a build is still running; open the 'tmp' "
								. "folder above and see if the archive file is growing in size. If it is not then your server has strict timeout constraints.", 'duplicator')
							);
						?> <br/>
					</div>
				</fieldset>
				
				<!-- LOGS -->
				<div id="dup-logs">
					<div style="font-weight:bold">
						<i class="fa fa-list-alt"></i> <a href='javascript:void(0)' style="color:maroon" onclick='Duplicator.OpenLogWindow(true)'> <?php _e('Package Log', 'duplicator');?> </a>
					</div> 
					<br/>
				</div>

			</div>
			
		</div>
	</div>
</form>

<script>
jQuery(document).ready(function($) {
	/*	----------------------------------------
	*	METHOD: Performs Ajax post to create a new package
	*	Timeout (10000000 = 166 minutes)  */
	Duplicator.Pack.Create = function() {

		var startTime;
		var endTime;

		var data = {action : 'duplicator_package_build', nonce: '<?php echo $ajax_nonce; ?>'}

		$.ajax({
			type: "POST",
			url: ajaxurl,
			dataType: "json",
			timeout: 10000000,
			data: data,
			beforeSend: function() {startTime = new Date().getTime();},
			complete:   function() {
				endTime = new Date().getTime();
				var millis = (endTime - startTime);
				var minutes = Math.floor(millis / 60000);
				var seconds = ((millis % 60000) / 1000).toFixed(0);
				var status = minutes + ":" + (seconds < 10 ? '0' : '') + seconds;
				$('#dup-msg-error-response-time span.data').html(status)
			},
			success:    function(data) { 
				$('#dup-progress-bar-area').hide(); 
				$('#dup-progress-area, #dup-msg-success').show(300);
				
				var Pack = data.Package;
				var InstallURL = Pack.StoreURL + Pack.Installer.File + "?get=1&file=" + Pack.Installer.File;
				var ArchiveURL = Pack.StoreURL + Pack.Archive.File   + "?get=1";
				
				$('#dup-btn-archive-size').append('&nbsp; (' + data.ZipSize + ')')
				$('#data-name-hash').text(Pack.NameHash || 'error read');
				$('#data-time').text(data.Runtime || 'unable to read time');
				
				//Wire Up Downloads
				$('#dup-btn-installer').on("click", {name: InstallURL }, Duplicator.Pack.DownloadFile  );
				$('#dup-btn-archive').on("click",   {name: ArchiveURL }, Duplicator.Pack.DownloadFile  );
					
			},
			error: function(data) { 
				$('#dup-progress-bar-area').hide(); 
				$('#dup-progress-area, #dup-msg-error').show(200);
				var status = data.status + ' -' + data.statusText;
				var response = (data.responseText != undefined && data.responseText.trim().length > 1) ? data.responseText.trim() : 'No client side error - see package log file';
				$('#dup-msg-error-response-status span.data').html(status)
				$('#dup-msg-error-response-text span.data').html(response);
				console.log(data);
			}
		});
		return false;
	}

	//Page Init:
	Duplicator.UI.AnimateProgressBar('dup-progress-bar');
	Duplicator.Pack.Create();

});
</script>