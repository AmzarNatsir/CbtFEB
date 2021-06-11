<!-- Header -->
<header id="head">
	<div class="container">
         <div class="heading-text">							
						<h1 class="animated flipInY delay1">Start Online Examination</h1>
						<p>The Online Examination System for Education</p>
					</div>
				<div class="fluid_container">                       
                <div class="camera_wrap camera_emboss pattern_1" id="camera_wrap_4">
                    <div data-thumb="<?php echo base_url();?>assets/images/slides/thumbs/img1.jpg" data-src="<?php echo base_url();?>assets/images/slides/img1.jpg">
                        <h2>We develop.</h2>
                    </div> 
                    <div data-thumb="<?php echo base_url();?>assets/images/slides/thumbs/img2.jpg" data-src="<?php echo base_url();?>assets/images/slides/img2.jpg">
                    </div>
                    <div data-thumb="<?php echo base_url();?>assets/images/slides/thumbs/img3.jpg" data-src="<?php echo base_url();?>assets/images/slides/img3.jpg">
                    </div> 
                </div><!-- #camera_wrap_3 -->
            </div><!-- .fluid_container -->
	</div>
</header>
<script>
jQuery(function()
{
  jQuery('#camera_wrap_4').camera({
  transPeriod: 500,
  time: 3000,
  height: '600',
  loader: 'false',
  pagination: true,
  thumbnails: false,
  hover: false,
  playPause: false,
  navigation: false,
  opacityOnGrid: false,
  imagePath: '<?php echo base_url();?>assets/images/'
  });

});
</script>
<!-- /Header -->