<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
<div class="element-container">
    
    <div class="image-field">
    
        <div class="member-ship-loader">

        </div>
        
        <label>Video</label>
        
        <input type="hidden" data-fieldname="element_type" name="element_type[<?=$i?>][<?=$c?>]" value="video_with_title_subtitle">
        
        <input data-fieldname="member_ship_image_id" name="member_ship_image_id[<?=$i?>][<?=$c?>]" type="hidden" class="member-ship-image-id">
        
        <div class="membership-image-container">
        
        </div>
        
        <input type="file" name="member_ship_video[<?=$i?>][<?=$c?>]" data-fieldname="member_ship_video" class="form-control">
    
    </div>
    
        <div class="text-field"><label>Title</label>
            
        <input type="text" name="member_ship_title[<?=$i?>][<?=$c?>]" data-fieldname="member_ship_title" class="form-control">
    
        </div>
    
    <div class="tex-field"><label>Sub Title</label>
    
        <input type="text" name="member_ship_sub_title[<?=$i?>][<?=$c?>]" data-fieldname="member_ship_sub_title" class="form-control">
    
    </div>
    
    <div class="click-to-add-element">Change Element</div>
    
    <div class="remove-to-add-element">Remove</div>

</div>
