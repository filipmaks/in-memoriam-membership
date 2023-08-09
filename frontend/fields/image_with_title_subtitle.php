<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

?>
 <div class="element-container">
                   
                   <div class="image-field">
                       
                       <div class="member-ship-loader"></div>
                       
                       <label>Image</label>
                       
                    <input data-fieldname="element_type" type="hidden" name="element_type[<?=$i?>][<?=$c?>]" 
                           value="image_with_title_subtitle"><input name="member_ship_image_id[<?=$i?>][<?=$c?>]" 
                           data-fieldname="member_ship_image_id" type="hidden" class="member-ship-image-id">
                    
                    <div class="membership-image-container">
                        
                   </div>
                    
                    <input type="file" data-fieldname="member_ship_image" name="member_ship_image[<?=$i?>][<?=$c?>]" 
                           class="form-control">
                   
                   </div>
                           
                           <div class="text-field"><label>Title</label>
                               
                               <input type="text" name="member_ship_title[<?=$i?>][<?=$c?>]" data-fieldname="member_ship_title" 
                                      
                                      class="form-control">
                           
                           </div>
                   
                   <div class="tex-field">
                       
                       <label>Sub Title</label>
                                          
                                          <input type="text" name="member_ship_sub_title[<?=$i?>][<?=$c?>]" 
                                                 data-fieldname="member_ship_sub_title" class="form-control">
                   
                   </div>
                   
                   <div class="click-to-add-element">Change Element</div>
                   
                   <div class="remove-to-add-element">Remove</div>
                       
               </div>
               
