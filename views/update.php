    <!-- Page Body -->
    <br>
    <div class="row text-center">
      <div class="small-12 medium-6 large-6 columns small-centered medium-center large-centered">
        <div data-alert class="alert-box <?php echo ($error) ? 'alert' : 'success';?> round" style="display: <?php echo ($message) ? "block" : "none"; ?>">
          <?php echo ($message) ? $message."\n" : "<!-- No messages -->\n"; ?>
        </div>
      </div>
    </div>
    <div class="row text-center">
      <p>Click the button to make update to all nodes.</p>
    </div>
    <div class="row">
      <div class="small-12 medium-8 large-6 columns small-centered medium-centered large-centered">
        <form data-abide action="<?php echo base_url();?>update/send_form" method="POST">
            <div class="row text-center">
              <br><input type="submit" value="Update" class="expand button">
            </div>
        </form>
        <br><p class="text-center"><a href="<?php echo base_url();?>faq">Questions? See the FAQ.</a></p>
      </div>  
    </div>