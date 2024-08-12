<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2023 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC <contact@vinades.vn>';
$lang_translator['createdate'] = '20/07/2023, 07:15';
$lang_translator['copyright'] = '@Copyright (C) 2010 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['main'] = 'Zalo';
$lang_module['settings'] = 'Settings';
$lang_module['general_settings'] = 'General settings';
$lang_module['zalo_official_account_id'] = 'Zalo Official Account ID (OAID)';
$lang_module['oa_create_note'] = '<ul><li>If you do not have a Zalo Official Account, please <a href="%s" target="_blank">click here</a> to create it.</li><li>Then <a href="%s" target="_blank">visit here</a> to get OAID</li></ul>';
$lang_module['app_id'] = 'App ID';
$lang_module['app_secret_key'] = 'App Secret Key';
$lang_module['app_note'] = '<ul><li>If you don\'t have an app, you can <a href="%s" target="_blank">create it here</a>.</li><li>Go to the <a href="%s" target="_blank">Manage Applications page</a>, click on the desired application to go to the settings page, copy the App ID and App Secret Key into the 2 corresponding boxes on the side (Note: If the app is in an inactive state, you need to click the switch in the upper right corner to switch the application to an active state).</li><li>Click on &ldquo;Official Account&rdquo; => &ldquo;Manage Official Account&rdquo; on the left menu bar to link to the official account you specified above.</li><li>Click on &ldquo;Official Account&rdquo; => &ldquo;General settings&rdquo;in the left menu bar, find the box &ldquo;Official Account Callback Url&rdquo; and click on the button &ldquo;Update&rdquo; to enter the following value:<code>%s</code>, then click the &ldquo;Save&rdquo; button next to it. Continue to &ldquo;Select permissions to request from OA&rdquo;, select all and click &ldquo;Save&rdquo; button</li><li>Click on &ldquo;Login&rdquo;, click on &ldquo;Web&rdquo; on the page that appears, enter in the box &ldquo;Home URL&rdquo; the value: <code>%s</code>, then add the following 2 values in box &ldquo;Callback URL&rdquo;: <code>%s</code> and <code>%s</code>, click on &ldquo;Save change&rdquo;.</li></ul>';
$lang_module['access_token'] = 'Access Token';
$lang_module['refresh_token'] = 'Refresh Token';
$lang_module['submit'] = 'Submit';
$lang_module['access_token_create'] = 'Create Access Token';
$lang_module['access_token_create_note'] = 'The Access Token is generated only when you have declared your OAID, App ID and App Secret Key';
$lang_module['access_token_copy'] = 'Copy from Zalo\'s access token generator';
$lang_module['access_token_copy_note'] = 'If you have trouble creating access token, you can use <a href="%s" target="_blank">Zalo\'s access token generator tool</a> (<a href="%s" target="_blank">see instructions here</a>) with the following parameters: Authentication method - "version 4"; Application - is the application that you have filled out above; Access token type - "OA Access Token"; Official Account - is the OA you filled out above here. After creating the Access token and Refresh token, copy them into the boxes below and click Save Changes';
$lang_module['oa_id_empty'] = 'Error: Official Account ID not been declared';
$lang_module['redirect_uri_empty'] = 'Error: Callback Url has not been declared';
$lang_module['app_id_empty'] = 'Error: App ID has not been declared';
$lang_module['app_seckey_empty'] = 'Error: App secret key has not been declared';
$lang_module['refresh_token_empty'] = 'Error: Refresh token not defined';
$lang_module['not_response'] = 'Error: No return data';
$lang_module['oa_id_incorrect'] = 'Error: The returned OAID does not match the OAID you declared';
$lang_module['refresh_token_expired'] = 'Error: Refresh token has expired';
$lang_module['refresh_token_expired_note'] = 'Error: The refresh token has expired. Let\'s re-generate the access token and refresh token!';
$lang_module['oa_info'] = 'OA information';
$lang_module['oa_id'] = 'OA\'s ID';
$lang_module['description'] = 'Description';
$lang_module['name'] = 'Name';
$lang_module['avatar'] = 'Avatar';
$lang_module['cover'] = 'Cover photo';
$lang_module['is_verified'] = 'Verification status';
$lang_module['refresh'] = 'Refresh';
$lang_module['updatetime'] = 'Update time';
$lang_module['verify_status_0'] = 'Unverified';
$lang_module['verify_status_1'] = 'Verified';
$lang_module['oa_info_update'] = 'Update OA information';
$lang_module['oa_clear'] = 'Clear data';
$lang_module['oa_clear_info'] = 'The saved data does not match the OA information. To continue, you need to clear the old data.';
$lang_module['followers'] = 'Followers';
$lang_module['user_id'] = 'ID';
$lang_module['user_id_by_app'] = 'ID by app';
$lang_module['display_name'] = 'Display name';
$lang_module['avatar120'] = 'Small avatar';
$lang_module['avatar240'] = 'Large avatar';
$lang_module['user_gender'] = 'Gender';
$lang_module['user_gender_0'] = '';
$lang_module['user_gender_1'] = 'Male';
$lang_module['user_gender_2'] = 'Female';
$lang_module['details'] = 'Details';
$lang_module['getprofile'] = 'Get profile';
$lang_module['getprofile2'] = 'Update profile';
$lang_module['getfollowers'] = 'Refresh followers list';
$lang_module['getfollowers_note'] = 'The browser may restart several times, depending on how many subscribers there are. Please do not close this browser while the update is in progress. You are ready?';
$lang_module['wait_update_info'] = 'Update in progress! The browser will reload after a few seconds. Please wait...';
$lang_module['unfollowers'] = 'List of unfollowers';
$lang_module['user_id_not_found'] = 'ID not found';
$lang_module['noupdate_info'] = 'Please wait % to update again';
$lang_module['noupdate_info_minute'] = 'minute,minutes';
$lang_module['follower_info'] = 'Follower information';
$lang_module['shared_info'] = 'Shared information returned by OA';
$lang_module['tags_info'] = 'Labels';
$lang_module['notes_info'] = 'Notes';
$lang_module['follower_info_in_db'] = 'Edit follower information';
$lang_module['phone'] = 'Phone';
$lang_module['phone_code'] = 'Country calling code';
$lang_module['phone_number'] = 'Phone number';
$lang_module['address'] = 'Address';
$lang_module['city'] = 'Province/City';
$lang_module['city_id'] = 'Province/City';
$lang_module['district'] = 'District';
$lang_module['district_id'] = 'District';
$lang_module['update_completed'] = 'Update completed!';
$lang_module['error_not_declared'] = 'Error: Because the &ldquo;%s&rdquo; field of the user has not been declared, it cannot be updated to OA';
$lang_module['tags'] = 'Tags';
$lang_module['add_tag'] = 'Add tag';
$lang_module['enter_tag_name'] = 'Please enter a tag name';
$lang_module['new_tag_empty'] = 'The tag name has not been entered. Tag must be at least 3 characters';
$lang_module['followertag_management'] = 'Follower tag management';
$lang_module['select_tag'] = 'Choose a tag';
$lang_module['or_new_tag'] = 'Or enter a new tag';
$lang_module['add_follower_tag'] = 'Assign tag';
$lang_module['tag_empty'] = 'Error: You have not selected or entered a tag';
$lang_module['tag_exists'] = 'This tag already exists';
$lang_module['no_tag_selected'] = 'Error: No tag selected';
$lang_module['tag_user_exists'] = 'This tag is being assigned to at least one user so it cannot be deleted';
$lang_module['click_to_remove'] = 'Click to remove';
$lang_module['click_to_delete'] = 'Click to delete';
$lang_module['delete_tag_confirm'] = 'Do you really want to remove this tag?';
$lang_module['no_tags_assigned'] = 'No tags assigned yet';
$lang_module['filter_by_tag'] = 'Filter by tag';
$lang_module['filter_by_type'] = 'Filter by type';
$lang_module['no_filter'] = 'Without a filter';
$lang_module['conversation'] = 'Conversation';
$lang_module['empty'] = 'No content yet!';
$lang_module['viewimg'] = 'View photo';
$lang_module['location'] = 'Location';
$lang_module['longitude'] = 'Longitude';
$lang_module['latitude'] = 'Latitude';
$lang_module['voice'] = 'Voice';
$lang_module['get_more'] = 'Get more';
$lang_module['last_50_messages'] = 'Last 50 messages';
$lang_module['last_100_messages'] = 'Last 100 messages';
$lang_module['proactive_messages_quota'] = 'Proactive messages quota';
$lang_module['proactive_messages_note'] = 'Only verified OA can send free proactive messages with this quota';
$lang_module['remain'] = 'Remain';
$lang_module['total'] = 'Total';
$lang_module['type'] = 'Type';
$lang_module['chat_text_empty'] = 'Please enter the text of the message';
$lang_module['auto_refresh'] = 'Auto';
$lang_module['site_attachment'] = 'Send photo from site';
$lang_module['internet_attachment'] = 'Send photo from internet';
$lang_module['zalo_attachment'] = 'Send photo from Zalo';
$lang_module['file_attachment'] = 'Send file from Zalo';
$lang_module['request_attachment'] = 'Send a profile request';
$lang_module['no_attachment'] = 'Plain Text';
$lang_module['attach'] = 'Photo';
$lang_module['attachment_empty'] = 'The attached file was not specified';
$lang_module['extension_not_supported'] = 'Only jpg and png formats are supported';
$lang_module['max_capacity_exceeded'] = 'File size exceeds maximum size (1 MB)';
$lang_module['image_is_invalid'] = 'Image is invalid';
$lang_module['image_from_localhost'] = 'You cannot use photo from localhost to be included directly in Zalo messages';
$lang_module['description_for_photo_empty'] = 'Please enter a description for the photo';
$lang_module['upload'] = 'Upload to Zalo';
$lang_module['type_image'] = 'jpg/png';
$lang_module['type_gif'] = 'gif';
$lang_module['type_file'] = 'pdf/doc/docx';
$lang_module['file_zaloid'] = 'Upload Code';
$lang_module['file_name'] = 'Name';
$lang_module['file_desc'] = 'Description';
$lang_module['file_type'] = 'Type';
$lang_module['file_addtime'] = 'Uploaded';
$lang_module['file_exptime'] = 'Expires';
$lang_module['file_select'] = 'Select file';
$lang_module['file_desc_select'] = 'Select file and description';
$lang_module['upload_form'] = 'Upload form';
$lang_module['choose_file'] = 'Choose file';
$lang_module['type_empty'] = 'Please select file type';
$lang_module['file_empty'] = 'File to upload unknown';
$lang_module['type_image_invalid'] = 'Please choose a file in jpg/png format';
$lang_module['type_gif_invalid'] = 'Please choose a file in gif format';
$lang_module['type_file_invalid'] = 'Please choose a file in pdf/doc/docx format';
$lang_module['type_image_exceedlimit'] = 'File size is larger than allowed (1MB)';
$lang_module['type_gif_exceedlimit'] = 'File size is larger than allowed (5MB)';
$lang_module['type_file_exceedlimit'] = 'File size is larger than allowed (5MB)';
$lang_module['file_size_not_exceed'] = 'The file size must not exceed';
$lang_module['description_empty'] = 'You have not entered a description';
$lang_module['viewfile'] = 'File preview';
$lang_module['nosupport'] = 'Message format not yet supported';
$lang_module['renewal'] = 'Renewal';
$lang_module['file_not_selected'] = 'File not selected';
$lang_module['delete'] = 'Delete';
$lang_module['delete_confirm'] = 'Do you really want to delete?';
$lang_module['info_request'] = 'Info request template';
$lang_module['title'] = 'Title';
$lang_module['subtitle'] = 'Subtitle';
$lang_module['alias'] = 'Alias';
$lang_module['image_url'] = 'Link to photo';
$lang_module['update'] = 'Update';
$lang_module['add_info_request'] = 'Add user info request template';
$lang_module['request_select'] = 'Choose';
$lang_module['request_not_selected'] = 'The sample ID has not been determined';
$lang_module['image_url_invalid'] = 'The link to the photo is not declared or is incorrect';
$lang_module['title_error'] = 'You have not entered a title';
$lang_module['subtitle_error'] = 'You have not entered a subtitle';
$lang_module['feature_for_verified_OA'] = 'Feature only for verified OA';
$lang_module['default_action'] = 'Action on click';
$lang_module['no_action'] = 'No action';
$lang_module['oa_open_url'] = 'Open URL';
$lang_module['oa_query_show'] = 'Send message to OA (visible on user side)';
$lang_module['oa_query_hide'] = 'Send message to OA (not visible on user side)';
$lang_module['oa_query_keyword'] = 'Send command keyword to OA (not visible on user side)';
$lang_module['oa_open_sms'] = 'Turn on the sms window on the phone';
$lang_module['oa_open_phone'] = 'Turn on the call window on the phone';
$lang_module['template_list'] = 'List of templates';
$lang_module['template_add'] = 'Add a template';
$lang_module['template_edit'] = 'Edit template';
$lang_module['element'] = 'Element';
$lang_module['url'] = 'URL';
$lang_module['content'] = 'Content';
$lang_module['template_not_selected'] = 'Template is not defined';
$lang_module['list_select'] = 'Choose';
$lang_module['auto_response_message'] = 'Auto response message';
$lang_module['mess_reply'] = 'Reply';
$lang_module['reply_remove'] = 'Close';
$lang_module['message_id_not_found'] = 'The message you indicated does not exist';
$lang_module['feature_not_to_reply_message'] = 'Feature does not apply to reply messages';
$lang_module['webhook_setup'] = 'Webhook Setup';
$lang_module['webhook_setup_note'] = 'Webhook setup is done only when you have declared OAID, App ID and App Secret Key';
$lang_module['oa_secrect_key'] = 'OA Secrect Key';
$lang_module['webhook_note'] = '<ul><li><a href="%s">Go here</a> and activate the plugin <code>zalo_webhook</code> by clicking on the button «Integrate»</li><li>Go to the page <a href="%s" target="_blank">Quản lý ứng dụng</a>, click on the desired application to go to the settings page, click on the button «Webhook».</li><li> In the box «Webhook URL» click on the button «Thay đổi», enter the value <code>%s</code> and click on the button «Cập nhật»</li><li> The line «OA Secrect Key» appears on the page that has just been refreshed, copy its value into the box «OA Secret Key» on the left</li><li>Enable all options in the area «Danh sách sự kiện webhook».</li><li>Determining the IP address of Zalo Webhook is required. Otherwise, the system will block the access of these webhooks, preventing the automatic update of the message.</li></ul>';
$lang_module['article'] = 'Article';
$lang_module['article_type'] = 'Type';
$lang_module['article_normal'] = 'Normal type articles';
$lang_module['article_video'] = 'Video type articles';
$lang_module['article_list'] = 'List of articles';
$lang_module['article_normal_list'] = 'List of normal type articles';
$lang_module['article_video_list'] = 'List of video type articles';
$lang_module['article_add'] = 'Add article';
$lang_module['article_edit'] = 'Edit article';
$lang_module['video'] = 'Uploaded Videos';
$lang_module['video_id'] = 'Video ID';
$lang_module['video_id_empty'] = 'Video ID not defined';
$lang_module['video_list'] = 'List of uploaded videos';
$lang_module['video_add'] = 'Add video';
$lang_module['video_view'] = 'Display type';
$lang_module['video_view_horizontal'] = 'Horizontal';
$lang_module['video_view_vertical'] = 'Vertical';
$lang_module['video_view_square'] = 'Square';
$lang_module['video_thumb'] = 'Thumbnail';
$lang_module['video_edit'] = 'Edit video information';
$lang_module['token_empty'] = 'Token returned from Zalo has not been determined';
$lang_module['file_size'] = 'Size';
$lang_module['file_status'] = 'Status';
$lang_module['status_check'] = 'Check';
$lang_module['author'] = 'Author';
$lang_module['author_empty'] = 'You have not entered the author of the article';
$lang_module['cover_type'] = 'Cover type';
$lang_module['cover_type_photo'] = 'Photo';
$lang_module['cover_type_video'] = 'Video';
$lang_module['cover_photo_url'] = 'Cover photo';
$lang_module['cover_photo_url_empty'] = 'You have not entered the link to the cover photo';
$lang_module['cover_video_id'] = 'Cover video ID';
$lang_module['cover_video_id_empty'] = 'You have not entered the ID of the cover video';
$lang_module['cover_view'] = 'Cover video display type';
$lang_module['cover_status'] = 'Show cover when viewing article details';
$lang_module['yes'] = 'Yes';
$lang_module['no'] = 'No';
$lang_module['general_info'] = 'General information';
$lang_module['body_type'] = 'Content type';
$lang_module['body_type_text'] = 'Text';
$lang_module['body_type_image'] = 'Image';
$lang_module['body_type_video'] = 'Video';
$lang_module['body_type_product'] = 'Product';
$lang_module['body_content'] = 'Text';
$lang_module['body_content_empty'] = 'Please enter text';
$lang_module['body_content_note'] = 'Is a paragraph without a line break. If you want to add a new paragraph, click on «Add content»';
$lang_module['body_photo_url'] = 'URL of the image';
$lang_module['body_photo_url_empty'] = 'Please enter URL of the image';
$lang_module['body_video_url'] = 'Video\'s URL';
$lang_module['body_video_id'] = 'Video ID';
$lang_module['body_video_content_empty'] = 'Video not defined';
$lang_module['body_thumb'] = 'Thumbnail';
$lang_module['body_thumb_empty'] = 'Thumbnail not defined';
$lang_module['body_caption'] = 'Caption for the image';
$lang_module['body_product_id'] = 'Product ID';
$lang_module['body_product_id_empty'] = 'Product ID not defined';
$lang_module['add_body'] = 'Add content';
$lang_module['delete_body'] = 'Delete content';
$lang_module['article_status'] = 'Show';
$lang_module['article_comment'] = 'Comments are allowed';
$lang_module['show'] = 'Yes';
$lang_module['hide'] = 'No';
$lang_module['related_medias'] = 'Related articles';
$lang_module['tracking_link'] = 'Link to track viewership';
$lang_module['add_related_article'] = 'Add related article';
$lang_module['video_avatar_empty'] = 'Thumbnail not defined';
$lang_module['related_article_remove'] = 'Remove related article';
$lang_module['zalo_id'] = 'Article ID';
$lang_module['addtime'] = 'Publication date';
$lang_module['get_zalo_id'] = 'Get ID';
$lang_module['get_zalo_id_title'] = 'Get article ID';
$lang_module['get_zalo_id_note'] = 'ID of article «%s» has not been defined. You have %s to retrieve the article ID';
$lang_module['article_not_selected'] = 'The article has not been selected';
$lang_module['not_defined'] = 'Not defined';
$lang_module['operation'] = 'Operation';
$lang_module['status_check_title'] = 'Check the status of the video';
$lang_module['status_check_note'] = 'Video «%s» has not been checked for status. You have %s to update the video\'s status';
$lang_module['sync'] = 'Sync';
$lang_module['getlist'] = 'Update from Zalo';
$lang_module['articles_filter_mess'] = 'First you need to filter articles by type';
$lang_module['article_select'] = 'Choose';
$lang_module['followers_list'] = 'Followers';
$lang_module['system_check'] = 'Check system compatibility';
$lang_module['directive'] = 'Directive';
$lang_module['required_value'] = 'Required';
$lang_module['current_value'] = 'Current';
$lang_module['result'] = 'Result';
$lang_module['recommedation'] = 'Recommedation';
$lang_module['suitable'] = 'Suitable';
$lang_module['notsuitable'] = 'Not suitable';
$lang_module['upload_max_filesize_not_suitable'] = 'The value of the upload_max_filesize directive is too small. Please fix this value in php.ini to 5M';
$lang_module['post_max_size_not_suitable'] = 'The value of the post_max_size directive is too small. Please fix this value in php.ini to 5M';
$lang_module['file_allowed_ext'] = 'File types allowed to upload';
$lang_module['file_allowed_ext_not_suitable'] = 'Requires permission to upload JPG, PNG, PDF, DOC and DOCX files (images, Adobe and document types) to the server. Please <a href="%s">click here</a> to allow these files';
$lang_module['nv_max_size'] = 'Maximum size of uploaded file';
$lang_module['nv_max_size_not_suitable'] = 'The minimum size of the uploaded file must be 5.00 MB. Please <a href="%s">click here</a> to adjust this value';
$lang_module['finally'] = 'Finally';
$lang_module['finally_suitable'] = 'You can upload files to Zalo and store them on the website\'s server';
$lang_module['finally_not_suitable'] = 'You can upload files to Zalo, but cannot store them on the website\'s server';
$lang_module['vnsubdivisions_settings'] = 'Administrative units of Vietnam';
$lang_module['callingcodes_settings'] = 'Country calling codes';
$lang_module['vnsubdivisions_code'] = 'Code';
$lang_module['vnsubdivisions_main_name'] = 'Official name';
$lang_module['vnsubdivisions_other_name'] = 'Other name';
$lang_module['vnsubdivisions_other_name_note'] = 'One line for each name';
$lang_module['vnsubdivisions_parent'] = 'Administrative units of %s';
$lang_module['provincial_vnsubdivisions'] = 'Provincial administrative units';
$lang_module['change_name_note'] = 'This name should follow the standards of the General Statistics Office of Vietnam. Are you sure you want to rename it?';
$lang_module['vnsubdivisions_error'] = 'Administrative unit does not exist';
$lang_module['vnsubdivisions_title_empty'] = 'The name of the administrative unit cannot be left blank';
$lang_module['country_code'] = 'Code';
$lang_module['country_name'] = 'Country name';
$lang_module['country_callcode'] = 'Calling code';
$lang_module['country_callcode_error'] = 'Please declare at least one calling code for this country';
$lang_module['templates'] = 'Message templates';
$lang_module['template'] = 'Message template';
$lang_module['template_plaintext_add'] = 'Add a new plain text message template';
$lang_module['template_request_add'] = 'Add a new info request message template';
$lang_module['template_plaintext_edit'] = 'Edit plain text message template';
$lang_module['template_request_edit'] = 'Edit info request message template';
$lang_module['plaintext'] = 'Plain text';
$lang_module['add_plaintext_from_templates'] = 'Get content from plain text message template';
$lang_module['chatbot'] = 'Chatbot';
$lang_module['file_on_site_server'] = 'File saved on the site server';
$lang_module['file_on_zalo'] = 'File on Zalo';
$lang_module['download'] = 'Download';
$lang_module['text_message_attached'] = 'The text message will be attached';
$lang_module['user_send_location'] = 'User sent a location message';
$lang_module['user_send_image'] = 'User sent image message';
$lang_module['user_send_link'] = 'User sent link message';
$lang_module['user_send_text'] = 'User sent text message';
$lang_module['user_send_sticker'] = 'User sent sticker message';
$lang_module['user_send_gif'] = 'User sent gif message';
$lang_module['user_send_audio'] = 'User sent voice message';
$lang_module['user_send_video'] = 'User sent video message';
$lang_module['user_send_file'] = 'User sent attachment';
$lang_module['user_received_message'] = 'User received message';
$lang_module['user_seen_message'] = 'User has read message';
$lang_module['user_submit_info'] = 'User agreed to share information';
$lang_module['follow'] = 'User followed Official Account';
$lang_module['unfollow'] = 'User unfollowed Official Account';
$lang_module['add_user_to_tag'] = 'User tagged';
$lang_module['shop_has_order'] = 'Order created';
$lang_module['oa_send_text'] = 'Official Account sent a text message';
$lang_module['oa_send_image'] = 'Official Account sent a image message';
$lang_module['oa_send_list'] = 'Official Account sent interactive message';
$lang_module['oa_send_gif'] = 'Official Account sent gif message';
$lang_module['oa_send_file'] = 'Official Account sent attachment';
$lang_module['action_sent_text_message'] = 'Send text message';
$lang_module['action_sent_image_message'] = 'Send image message';
$lang_module['action_sent_file_message'] = 'Send file';
$lang_module['event'] = 'Event';
$lang_module['action'] = 'Action';
$lang_module['parameter'] = 'Parameter';
$lang_module['parameter_edit'] = 'Edit parameter';
$lang_module['action_empty'] = 'Define the type of action';
$lang_module['parameter_empty'] = 'Please specify parameter for this action or choose no action';
$lang_module['qrcode'] = 'QR-code';
$lang_module['zalo_homepage'] = 'Official Account URL';
$lang_module['info_request_link'] = 'Info request link';
$lang_module['invitation_to_follow'] = 'Invitation to follow URL';
$lang_module['category'] = 'Category';
$lang_module['hotline'] = 'Hotline';
$lang_module['action_for_zalo_event'] = 'Action for Zalo event';
$lang_module['action_for_command_keyword'] = 'Action for command keyword';
$lang_module['command_keyword'] = 'Command keyword';
$lang_module['if_command_keyword'] = 'Command keyword';
$lang_module['command_keyword_note'] = 'The command keyword is a secret keyword that is sent to the user in the form of an interactive message. When the user clicks on this keyword, Zalo will notify the OA of this action in the form of a webhook. When this message is received, the system will take the appropriate action predefined here';
$lang_module['keyword_empty'] = 'Please declare this command keyword';
$lang_module['keyword_select'] = 'Choose';
$lang_module['chatbot_note'] = 'Chatbot is only deployed on the internet environment. You are using localhost so this does not work';
$lang_module['zalowebhook_ips'] = 'IP addresses of Zalo Webhook';
$lang_module['zalowebhook_ip_check'] = 'Check';
$lang_module['zalowebhook_ip_update'] = 'Update';
$lang_module['zalowebhook_ip_check_note'] = '<ul><li>To get information about the IP address of Zalo webhook, you need to first <a href="%s" target="_blank">visit here</a>, navigate to the «List of webhook events» area and click on one of the buttons «Test».</li><li>In the pop-up window, click the «Submit Event» button.</li><li>If you receive the message «Event has been sent to Webhook», click the «Update» button below.</li><li>If you get a different message, click the «Submit event» button again</li></ul>';
$lang_module['zalowebhook_ip_input_note'] = 'Enter each value on one line';
$lang_module['error_code'] = 'Error code';
$lang_module['error-32'] = 'Error code -32: Request/minute limit exceeded';
$lang_module['error-200'] = 'Error code -200: Token is invalid';
$lang_module['error-201'] = 'Error code -201: Invalid parameter';
$lang_module['error-202'] = 'Error code -202: Invalid Mac';
$lang_module['error-204'] = 'Error code -204: Official Account has been deleted';
$lang_module['error-205'] = 'Error code -205: Official Account does not exist';
$lang_module['error-207'] = 'Error code -207: Official Account is not registered as a 3rd party';
$lang_module['error-208'] = 'Error code -208: Official Account does not have a secret key';
$lang_module['error-209'] = 'Error code -209: This api is not supported';
$lang_module['error-210'] = 'Error code -210: Parameter exceeds the allowable limit';
$lang_module['error-211'] = 'Error code -211: Out of quota';
$lang_module['error-212'] = 'Error code -212: Official Account has not registered this api';
$lang_module['error-213'] = 'Error code -213: Users are not interested in Official Account';
$lang_module['error-214'] = 'Error code -214: Post is being processed';
$lang_module['error-215'] = 'Error code -215: Invalid App id';
$lang_module['error-216'] = 'Error code -216: Access token is invalid';
$lang_module['error-217'] = 'Error code -217: The user has blocked the invitation of interest';
$lang_module['error-218'] = 'Error code -218: Out of quota to receive';
$lang_module['error-221'] = 'Error code -221: Official Account not verified';
$lang_module['error-305'] = 'Error code -305: Official Account can only reply to messages from users within 48 hours';
$lang_module['error-311'] = 'Error code -311: Out of user replies';
$lang_module['error-320'] = 'Error code -320: Your application needs to connect to Zalo Business Account to use the paid feature';
$lang_module['error-321'] = 'Error code -321: The Zalo Business Account associated with the App has run out of money or cannot be paid';
$lang_module['video_status_0'] = 'Unknown';
$lang_module['video_status_1'] = 'Available';
$lang_module['video_status_2'] = 'Is locked';
$lang_module['video_status_3'] = 'Is being processed';
$lang_module['video_status_4'] = 'Processing failed';
$lang_module['video_status_5'] = 'Deleted';
$lang_module['oa_type'] = 'Official Account type';
$lang_module['oa_type_2'] = 'Enterprise';
$lang_module['oa_type_4'] = 'State agency';
$lang_module['cate_name'] = 'Activity category';
$lang_module['num_follower'] = 'Total followers';
$lang_module['package_name'] = 'Package name';
$lang_module['package_valid_through_date'] = 'Package validity period';
$lang_module['linked_zca'] = 'Linked ZCA wallet information';
$lang_module['is_sensitive'] = 'Under 18';
$lang_module['is_sensitive_0'] = 'No';
$lang_module['is_sensitive_1'] = 'Yes';
