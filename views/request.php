    <!-- Page Body -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <br>
    <div class="row text-center">
      <div class="small-12 medium-6 large-6 columns small-centered medium-center large-centered">
        <div data-alert class="alert-box <?php echo ($error) ? 'alert' : 'success';?> round" style="display: <?php echo ($message) ? "block" : "none"; ?>">
          <?php echo ($message) ? $message."\n" : "<!-- No messages -->\n"; ?>
        </div>
      </div>
    </div>
    <div id="output"></div>
    <div class="row text-center">
        <p>Available Requests</p>
      <?php 
        if (count($requests) == 0)
            echo '<p>No available requests</p>';
        else{
            $num = 1;
            foreach ($requests as $request){
                echo '<div style="text-align: left"><b>Request number '.$num.'</b><br><br>';
                echo 'Name: '.$request[1].'<br>';
                echo 'Email: '.$request[2].'<br>';
                echo 'Essay: '.$request[3].'<br><br>';
                echo '<span class="approve button tiny" id="'.$request[0].'"> Approve<br></div>';
                $num ++;
            }
        }
        ?>
    </div>
    <script>
        <?php 
            echo 'var url = "'.base_url().'request/add_user";';
        ?>
        
        $('.approve').click(function(){
            var request_id = $(this).attr('id');
            var button = $(this);
            var data = {'id': request_id};
            $.post(url, data, function(){
                button.off('click');
                button.html('Approved!');
                button.attr('class', 'approve button tiny secondary')
                button.parent().delay(1000).hide(1000);
            });
        });

    
    </script>
    <div class="row">
        <br><p class="text-center"><a href="<?php echo base_url();?>faq">Questions? See the FAQ.</a></p>
    </div>  