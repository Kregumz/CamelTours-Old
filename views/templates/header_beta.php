<!DOCTYPE html>
  <head>
    <!--place google maps header info here-->
    <?php if ($title == 'Catalog') echo $map['js']; ?>
    
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>CamelTours</title>
    <link rel="shortcut icon" href="<?php echo base_url();?>media/img/CamelToursIcon.ico"/>
    <!-- CSS links -->
    <link rel="stylesheet" href="<?php echo base_url();?>media/css/foundation.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>media/css/foundation.mod.css">
    <link rel="stylesheet" href="<?php echo base_url();?>media/css/glyphicons.css">

    <?php if ($title == 'Node') echo '<link rel="stylesheet" href="'.base_url().'media/css/dropzone.css">'."\n    "; ?><!-- End CSS links -->
    
    <script src="<?php echo base_url();?>media/js/vendor/modernizr.js"></script>
  </head>
  <body>
