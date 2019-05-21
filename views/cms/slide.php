    <!-- Page Body -->
    <br>
    <div class="row">
      <h4 class="text-center"><a href="<?php echo $node_url;?>0">&lt;&lt;</a>&nbsp;&nbsp;&nbsp;<a href="<?php echo $node_url;?><?php echo ($seq_num - 1 > 0) ? ($seq_num - 1) : 0; ?>">&lt;</a>&nbsp;&nbsp;&nbsp; <?php echo ($is_audio_slide) ? 'Audio' : 'Slide '.$seq_num; ?> &nbsp;&nbsp;&nbsp;<a href="<?php echo $node_url;?><?php echo ($seq_num + 1 < $total_slides_minus_audio) ? ($seq_num + 1) : $total_slides_minus_audio; ?>">&gt;</a>&nbsp;&nbsp;&nbsp;<a href="<?php echo $node_url;?><?php echo $total_slides_minus_audio;?>">&gt;&gt;</a></h4>
      <br><br>
    </div>
    <div class="row text-center">
      <?php if ($is_audio_slide): ?><!-- Audio Slide -->
        <?php if ($audio_not_found): ?><!-- No Audio Found -->
          <p>Uh oh! It looks like you haven't added an audio file for this node! Why don't you <a href="<?php echo $upload_url;?>">upload one now</a>?</p>
        <?php else: ?><!-- Audio Found -->
          <audio controls style="max-width: 90%;">
            <source src="<?php echo base_url().$media_uri;?>" type="audio/mpeg">
            Your browser does not support the audio tag.
          </audio>
          <div class="row text-center">
            <div class="small-10 medium-5 large-3 columns small-centered medium-center large-centered">
              <form action="<?php echo base_url().'cms/slide/'.$tour_id.'/'.$node_id.'/'.$seq_num.'/send-form';?>" method="POST">
                <div class="row"><br><br>
                  <input type="checkbox" name="delete"><label>Delete Audio</label>
                  <input type="submit" value="Submit" class="tiny button">
                </div>
              </form>
            </div>
          </div>
        <?php endif; ?><!-- End Audio Check -->
      <?php else: ?><!-- Image Slide -->
        <div class="small-12 medium-6 large-6 columns">
          <img src="<?php echo base_url().$media_uri;?>">
        </div>
        <div class="small-12 medium-6 large-6 columns">
          <div class="row text-center">
            <div class="small-11 medium-11 large-11 columns small-centered medium-center large-centered">
              <div data-alert class="alert-box <?php echo ($error) ? 'alert' : 'success';?> round" style="display: <?php echo ($message) ? "block" : "none"; ?>">
                <?php echo ($message) ? $message."\n" : "<!-- No messages -->\n"; ?>
              </div>
            </div>
          </div>
          <form class="text-left" action="<?php echo base_url().'cms/slide/'.$tour_id.'/'.$node_id.'/'.$seq_num.'/send-form';?>" method="POST">
            <fieldset>
              <div class="row">
                <label>Image Caption</label>
                <input type="text" name="caption" value="<?php echo $caption;?>">
              </div>
              <div class="row">
                <label>Move image to...</label>
                <select name="seqn">
                  <?php for ($i = 1; $i <= $total_slides_minus_audio; $i++) { $a = ($i == $seq_num) ? ' selected' : ''; echo '<option value="'.$i.'"'.$a.'>Slide '.$i.'</option>'; } ?>
                </select>
              </div>
              <div class="row"><br>
                <input type="checkbox" name="delete"><label>Delete Image</label>
              </div>
              <div class="row text-center">
                <br><input type="submit" value="Submit" class="expand button">
              </div>
            </fieldset>
          </form>
        </div>
      <?php endif; ?><!-- End Slide Details -->
    </div>
    <div class="row text-center">
      <br><br>
      <p><a href="<?php echo $upload_url;?>">Back to the upload page.</a></p>
    </div>
