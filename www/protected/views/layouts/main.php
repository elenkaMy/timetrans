<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo CHtml::encode($this->title); ?></title>
        <meta name="viewport" inner="width=device-width, initial-scale=1">
        <?php if (!empty($this->seoDescription)): ?>
            <meta name="description" content="<?php echo CHtml::encode($this->seoDescription) ?>">
        <?php endif; ?>
        <?php if (!empty($this->seoKeywords)): ?>
            <meta name="keywords" content="<?php echo CHtml::encode($this->seoKeywords) ?>">
        <?php endif; ?>
        <meta name="viewport" content="width=device-width">

        <?php
            Yii::app()->clientScript
                ->registerPackage('app_core')
            ;
        ?>

    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">
                <?php echo Yii::t('core', 'You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.') ?>
            </p>
        <![endif]-->

        <!-- Site or application content starts here -->

        <div class="header-wrap">
            <div class="c-align">
                <header class="site-header">
                    <span class="logo">
                        Gelikon.by
                    </span>
                    <div id="header">
                        <div class="contacts">
                            <?php echo Setting::byFixedName('contacts')->value ?>
                        </div>
                    </div>
                </header>
            </div>
        </div>
        
        <nav class="header-menu">
            <div class="c-align">
                <ul class="menu menu-horizontal">
                    <?php $this->widget('application.widgets.MainMenu',array(
                        'htmlOptions'    => array('class' => ''),
                        'items' => array_map(function(MenuItem $i){
                            return Yii::app()->menuItemHelper->types[$i->item_type]->getMenuWidgetConfig($i);
                        }, Menu::model()->byFixedName('main_menu')->parentMenuItems),
                    )); ?>
                </ul>
            </div>
        </nav>

        <div class="content-wrap footer-pad">
            <div class="c-align">
                <div class="content-area clearfix">
                    <section class="content-section">
                        <?php echo $content?>
                    </section>
                    
                    <?php /*
                        <span class="col-title"><p>ОТ ПРОИЗВОДИТЕЛЯ<br>Художественная&nbsp; ковка</p></span>
                        
                        <ul class="products-list clearfix">
                            <?php foreach(Product::model()->findAllByAttributes(array('visible' => '1')) as $pages):?>
                                <li>
                                    <a href="<?php echo Yii::app()->createUrl('product/product/index', array('record' => $pages)) ?>">
                                        <?php echo CHtml::image($pages->file->path,'', array('height'=> '172', 'width'=> '199'))?>
                                        <div style="display: inline">
                                            <?php echo CHtml::encode($pages->product_name)?>
                                        </div>
                                    </a>
                                    <div class="price">
                                        Цена: <span class="num"><?php echo CHtml::encode($pages->price)?></span>
                                        <span class="cur">руб.</span>
                                    </div>

                                </li>
                            <?php endforeach;?>
                        </ul>
                        <?php echo Setting::byFixedName('text_main')->value ?>
                     * 
                     */?>
                    <aside class="col-a">
                        <span class="col-title">Каталог</span>
                        <nav>
                            <?php $this->widget('application.widgets.MainMenu',array(
                                'htmlOptions'    => array('class'=>"menu catalog-menu", 'id'=>''),
                                'items' => array_map(function(MenuItem $i){
                                    return Yii::app()->menuItemHelper->types[$i->item_type]->getMenuWidgetConfig($i);
                                }, Menu::model()->byFixedName('left_menu')->parentMenuItems),
                            )); ?>
                        </nav>
                    </aside>
                    <aside class="col-b">
                        <div class="b-cart shop-cart-short-info">
                            <div class="favourites">
                                <?php $this->renderPartial('//layouts/_favorites');?>
                            </div>
                        </div>
                    </aside>

                    
                <!-- Favourites -->
                <?php /*
            <div class="favourites">
                <?php $this->renderPartial('//layouts/_favorites');?>
            </div>
                <?php echo $content; ?>
                 * 
                 */?>
                
                </div>
            </div>
        </div>
              
        
        
        <div class="footer-wrap">
            <div class="c-align">
                <footer class="site-footer">
                    <div class="footer-menu">
                            <?php $this->widget('application.widgets.MainMenu',array(
                                //'activeCssClass' => 'menu-item-type-custom menu-item-object-custom current-menu-item current_page_item',
                                'htmlOptions'    => array('class'=>"menu menu-horizontal", 'id'=>''),
                                'items' => array_map(function(MenuItem $i){
                                    return Yii::app()->menuItemHelper->types[$i->item_type]->getMenuWidgetConfig($i);
                                }, Menu::model()->byFixedName('main_menu')->parentMenuItems),
                            )); ?>
                    </div>                    
                    <div class="custom-footer-content clearfix">
                        <div class="b-copyrights">
                            <?php echo Setting::byFixedName('contacts_footer_right')->value ?>
                        </div>
                        <div class="b-contacts">
                            <?php echo Setting::byFixedName('contacts_footer_left')->value ?>
                        </div>
                    </div>
                    <div class="counter"><noindex><!-- Yandex.Metrika counter --> <script type="text/javascript">
                        (function (d, w, c) {
                            (w[c] = w[c] || []).push(function() {
                                try {
                                    w.yaCounter23824939 = new Ya.Metrika({id:23824939,
                                            clickmap:true,
                                            trackLinks:true,
                                            accurateTrackBounce:true});
                                } catch(e) { }
                            });

                            var n = d.getElementsByTagName("script")[0],
                                s = d.createElement("script"),
                                f = function () { n.parentNode.insertBefore(s, n); };
                            s.type = "text/javascript";
                            s.async = true;
                            s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

                            if (w.opera == "[object Opera]") {
                                d.addEventListener("DOMContentLoaded", f, false);
                            } else { f(); }
                        })(document, window, "yandex_metrika_callbacks");
                        </script> <noscript>&amp;lt;div&amp;gt;&amp;lt;img src="//mc.yandex.ru/watch/23824939" style="position:absolute; left:-9999px;" alt="" /&amp;gt;&amp;lt;/div&amp;gt;</noscript> <!-- /Yandex.Metrika counter --></noindex></div>
                </footer>
            </div>
        </div>
        <!-- //Site or application content ends here -->
        <div id="lightboxOverlay" style="display: none;"></div>
        <div id="lightbox" style="display: none;"><div class="lb-outerContainer"><div class="lb-container"><img class="lb-image"><div class="lb-nav"><a class="lb-prev"></a><a class="lb-next"></a></div><div class="lb-loader"><a class="lb-cancel"><img src="/img/loading.gif"></a></div></div></div><div class="lb-dataContainer"><div class="lb-data"><div class="lb-details"><span class="lb-caption"></span><span class="lb-number"></span></div><div class="lb-closeContainer"><a class="lb-close"><img src="/img/close.png"></a></div></div></div></div>
    </body>
</html>
