    <!-- Page Body -->
    <br>
    <div class="row text-center">
      <div class="small-12 medium-6 large-6 columns small-centered medium-center large-centered">
        <div data-alert class="alert-box alert round" style="display: <?php echo ($error) ? "block" : "none"; ?>">
          <?php echo ($error) ? $error."\n" : "<!-- No errors -->\n"; ?>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="small-12 medium-12 large-12 columns small-centered medium-centered large-centered">
        <p>Upload your images and audio by dragging-and-dropping the files into the space below or by clicking on the space. Current files are shown as small thumbnails in slideshow order, and clicking on one of the thumbnails will bring you to the edit screen of that slide.</p>
      </div>
    </div>
    <div class="row">
      <div class="small-12 medium-10 large-10 columns small-centered medium-centered large-centered">
        <form action="<?php echo base_url().'cms/node/'.$tour_id.'/'.$node_id.'/send-form';?>" class="dropzone" id="nodezone" style="border: solid 1px #999999;" method="POST" enctype="multipart/form-data">
          <div class="dz-default dz-message">
            <span></span>
          </div>
          <div class="fallback">
            <?php
              echo '<!-- Image Thumbnails -->'."\n";
              foreach ($existing_files as $key => $value) {
                echo '            <div class="thumb-holder">'."\n";
                echo '              <a class="th" href="'.base_url().'cms/slide/'.$tour_id.'/'.$node_id.'/'.$value['seqn'].'">'."\n";
                echo '                <img src="'.base_url().$value['thmb'].'" style="height: 100px; width: 100px;">'."\n";
                echo '              </a>'."\n";
                echo '            </div>'."\n";
              }
            ?>
            <br><br>
            <p><b>Note:</b> It looks like your browser doesn't support drag n' drop uploads. Please use the fallback form below.</p>
            <input type="hidden" name="unsupported" value="unsupported">
            <input type="file" name="file" multiple />
            <br><input type="submit" value="Upload">
          </div>
        </form>
      </div>
    </div>
    <div class="row text-center">
      <p><b><a href="<?php echo base_url().'ct/u'.$user_id.'/t'.$tour_id.'/n'.$node_id.'/';?>" target="_blank">View Node</a></b> &nbsp;&bull;&nbsp; <b><a href="<?php echo base_url().'ct/u'.$user_id.'/t'.$tour_id.'/n'.$node_id.'/qr.png';?>" target="_blank">QR Code</a></b> &nbsp;&bull;&nbsp; <b><a href="<?php echo base_url().'cms/node-settings/'.$tour_id.'/'.$node_id;?>">Node Settings</b></a></p>
    </div>
    <div class="row">
      <div class="small-12 medium-12 large-12 columns small-centered medium-centered large-centered">
        <p><b>Note:</b> You cannot upload more than twelve files or files larger than 2 MB each due to the limits of offline storage. If your files are too big, you can shrink them using a free image editor (like GIMP) or a free audio editor (like Audacity) and then upload them to your node. <b>Accepted file types:</b> .jpg, .jpeg, .gif, .png, and .bmp for images; .mp3 for audio files. Please see the FAQ if you have any additional questions.</p>
      </div>
    </div>
    <div class="row text-center">
      <br><p class="text-center"><a href="<?php echo base_url();?>faq">Questions? See the FAQ.</a></p>
      <p><a href="<?php echo base_url().'cms/home/'.$tour_id;?>">Back to your homepage.</a></p>
       <!--<br><p class="text-center"> If you are the user who owns this tour OR the site administrator, you can access the page.</p>-->
    </div>

    <script src="/media/js/dropzone.min.js"></script>
    <script>
      var img_index = 1;
      var img_order = [];
      var slide_url = "<?php echo base_url().'cms/slide/'.$tour_id.'/'.$node_id.'/';?>";
      var updir = "<?php echo '/ct/u'.$user_id.'/t'.$tour_id.'/n'.$node_id.'/media/';?>";
      Dropzone.options.nodezone = {
        dictDefaultMessage: '',
        maxFilesize: 2,
        maxFiles: 12,
        dictMaxFilesExceeded: "You can only upload twelve files per node.",
        acceptedFiles: '.jpg,.jpeg,.gif,.png,.bmp,.mp3',
        init: function() {
          thisDropzone = this;
          $.getJSON("<?php echo base_url().'cms/node/'.$tour_id.'/'.$node_id.'/send-form';?>", function(data) {
            if (data) {
              $.each(data, function(key, value) {
                var mockFile = { name: value.name, size: value.size };
                thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                thisDropzone.options.thumbnail.call(thisDropzone, mockFile, '/'+value.thmb);
                if (isImage(mockFile)) {
                  img_order.push(mockFile);
                  $(mockFile.previewElement).wrap('<a href="' + slide_url + img_order.length + '"></a>');
                }
                else {
                  $(mockFile.previewElement).wrap('<a href="' + slide_url + 0 + '"></a>');
                }
                thisDropzone.options.maxFiles--;
              });
            }
          });
          this.on('addedfile', function(file) {
            if (isImage(file) && file.size<2097152)
              img_order.push(file)
            else if (isMP3(file))
              thisDropzone.options.thumbnail.call(thisDropzone, file, '/media/img/mp3thumb.png');
          });
          this.on('sending', function(file, xhr, formData) {
            if (isImage(file)) {
              var index = $.inArray(file, img_order);
              formData.append('seq_num', index + 1);
            }
            else
              formData.append('seq_num', 0);
          });
          thisDropzone.on('success', function(file) {
            if (isImage(file)) {
              var index = $.inArray(file, img_order);
              $(file.previewElement).wrap('<a href="' + slide_url + (index + 1) + '"></a>');
            }
            else
              $(file.previewElement).wrap('<a href="' + slide_url + 0 + '"></a>');
          });
        }
      };
      function isImage(file) {
        var x = file.name.split('.').pop().toLowerCase();
        return (x == 'jpg' || x == 'jpeg' || x == 'gif' || x == 'png' || x == 'bmp');
      }
      function isMP3(file) {
        return file.name.split('.').pop().toLowerCase() == 'mp3';
      }
    </script>