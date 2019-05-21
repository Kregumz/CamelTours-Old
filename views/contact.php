    <!-- Page Body -->
    <br>
    <div class="row text-center">
      <div class="small-12 medium-6 large-6 columns small-centered medium-center large-centered">
        <div data-alert class="alert-box alert round" style="display: <?php echo ($error) ? "block" : "none"; ?>">
          <?php echo ($error) ? $error."\n" : "<!-- No errors -->\n"; ?>
        </div>
      </div>
    </div>
    <div class="row text-center">
      <p>Please contact us either by filling out the form below or by courier pigeon.</p>
    </div>
    <div class="row">
      <div class="small-12 medium-8 large-6 columns small-centered medium-centered large-centered">
        <form data-abide action="<?php echo base_url();?>contact/send-form" method="POST">
          <fieldset>
            <div class="row">
              <label>Your Full Name *</label>
              <input type="text" name="name" value="<?php echo isset($form_data['name']) ? $form_data['name'] : ''; ?>" required placeholder="e.g. John Smith">
              <small class="error">Your name is required.</small>
            </div>
            <div class="row">
              <label>Your Email *</label>
              <input type="text" name="email" value="<?php echo isset($form_data['email']) ? $form_data['email'] : ''; ?>" required pattern="email" placeholder="e.g. jsmith@example.com">
              <small class="error">A valid email address is required.</small>
            </div>
            <div class="row">
              <label>Message *</label>
              <textarea name="message" required placeholder="Questions? Comments? Thoughts?"><?php echo isset($form_data['message']) ? $form_data['message'] : ''; ?></textarea>
              <small class="error">A message is required.</small>
            </div>
            <div class="row">
              <label>What is <?php echo $num1;?> + <?php echo $num2;?>? *</label>
              <input type="text" name="sum" required placeholder="Please enter the sum of the numbers.">
              <small class="error">Please answer the security question.</small>
              <input type="hidden" name="num1" value="<?php echo $num1;?>">
              <input type="hidden" name="num2" value="<?php echo $num2;?>">
            </div>
            <div class="row text-center">
              <br><input type="submit" value="Submit Form" class="expand button">
            </div>
          </fieldset>
        </form>
      </div>  
    </div>
