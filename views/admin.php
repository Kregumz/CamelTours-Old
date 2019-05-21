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
      
    </div>
    <div class="row">
      <div class="small-12 medium-8 large-6 columns small-centered medium-centered large-centered">
            <a class="button tiny" href=<?php echo base_url();?>request>Review Registration Requests</a>
            <br>
            <a class="button tiny" href=<?php echo base_url();?>update>Rebuild All Nodes and Tours</a>
            <br>
          <p class="text-center"><a href="<?php echo base_url();?>faq">Questions? See the FAQ.</a></p>
      </div>  
    </div>