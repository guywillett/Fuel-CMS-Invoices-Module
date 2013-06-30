    <style>.unpaid{color:blue;}.overdue,.overdue:visited{color:red;}</style>
   <div style="float:left;margin-right:30px;"> <h3>Contact Chamsoft</h3>
    <span>Email: <a href="mailto:info@chamsoft.co.uk">Guy at Chamsoft</a></span></div>
		<div style="float:left;margin-right:30px;"><h3>Unpaid Invoices</h3>
        
		<ul class="nobullets">
		<?php foreach($invoices as $r) : ?>
        <?php $class = strtotime($r['due_date']) > time() ? "unpaid" : "overdue" ?>
		<li><a href="<?=fuel_url('invoices/edit/'.$r['id'])?>" class="<?=$class?>" ><?=$r['name']."  (".date("M jS Y",strtotime($r['date']))?>)</a></li>
		<?php endforeach; ?>
		</ul>
        
        </div>