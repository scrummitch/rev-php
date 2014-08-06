Rev-PHP
=======

Rev.com PHP API because they dont have one

Rev.com is a transcription API service

*Basic Usage:*

 1. Configure with your access credentials
    <code>
    <?php
      $rev = new Rev('APP_ID', 'API_KEY');
    ?>
    </code>

 2. Make requests
    <code>
    <?php
      $orders = $rev->getOrders();
      var_dump($orders);
    ?>
    </code>
