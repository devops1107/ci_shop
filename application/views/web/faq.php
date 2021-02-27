

<div id="content">
	<!-- ****************** Breadcrumb Section	****************** -->
	
    <section class="inner-content">
    	<div class="container">
        	<!-- ****************** FAQ's Section	****************** -->
        	<div class="faq-page">
            	<div class="heading"><span><?=$this->lang->line('faq')?></span></div>
                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                    <?php 
                    $i = 0;
                    foreach ($faq_contents as $faqs) 
                    {
                        $i++;
                        ?>
                        <div class="panel panel-default">
                        	<div class="panel-heading" role="tab" id="heading<?= $faqs['faq_id']; ?>">
                                <h4 class="panel-title">
                                <a class="<?php echo ($i == 1) ? '' : 'collapsed'; ?>" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $faqs['faq_id']; ?>" aria-expanded="<?php echo ($i == 1) ? 'true' : 'false'; ?>" aria-controls="collapseOne"><?= $faqs['faq_question']; ?></a>
                                </h4>
                            </div>
                            <div id="collapse<?= $faqs['faq_id']; ?>" class="panel-collapse collapse <?php echo ($i == 1) ? 'in' : ''; ?>" role="tabpanel" aria-labelledby="heading<?= $faqs['faq_id']; ?>">
                                <div class="panel-body"><?= $faqs['faq_answer']; ?>
                                </div>
                            </div>
                        </div>
                        <?php 
                    }   ?>

                </div>
            </div>
        </div>
    </section>

    
</div>
