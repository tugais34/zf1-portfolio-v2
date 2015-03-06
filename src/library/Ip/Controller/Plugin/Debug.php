<?php 
class Ip_Controller_Plugin_Debug extends ZFDebug_Controller_Plugin_Debug
{
	public static $standardPlugins = array('Cache', 'Html', 'Doctrine2', 'Exception', 'File', 'Memory', 'Registry', 'Time', 'Variables');

	protected $_version = ZFDebug_Version::VERSION;

 	/**
     * Contains extrafile identifier name
     *
     * @var string
     */
	protected $_identifier = 'zfdebug';

	/**
     * Sets options of the Debug Bar
     *
     * @param array $options
     * @return ZFDebug_Controller_Plugin_Debug
     */
    public function setOptions(array $options = array())
    {
    	parent::setOptions($options);

    	if (isset($options['css_path'])) {
            $this->_options['css_path'] = $options['css_path'];
        }

        if (isset($options['js_path'])) {
            $this->_options['js_path'] = $options['js_path'];
        }
    }

    /**
     * Defined by Zend_Controller_Plugin_Abstract
     */
    public function dispatchLoopShutdown()
    {
        if ($this->getRequest()->isXmlHttpRequest()) {
            return;
        }

        $disable = Zend_Controller_Front::getInstance()->getRequest()->getParam('ZFDEBUG_DISABLE');
        if (null !== $disable) {
            return;
        }
        
        $html = '';

        /**
         * Creating menu tab for all registered plugins
         */
        foreach ($this->_plugins as $plugin)
        {
            $panel = $plugin->getPanel();
            if ($panel == '') {
                continue;
            }

            /* @var $plugin ZFDebug_Controller_Plugin_Debug_Plugin_Interface */
            $html .= '<div id="ZFDebug_' . $plugin->getIdentifier()
                  . '" class="ZFDebug_panel">' . $panel . '</div>';
        }

    $html .= '<div id="ZFDebug_info">';

        /**
         * Creating panel content for all registered plugins
         */
        foreach ($this->_plugins as $plugin)
        {
            $tab = $plugin->getTab();
            if ($tab == '') {
                continue;
            }

            /* @var $plugin ZFDebug_Controller_Plugin_Debug_Plugin_Interface */
            $html .= '<span class="ZFDebug_span clickable" onclick="ZFDebugPanel(\'ZFDebug_' . $plugin->getIdentifier() . '\');">';
            $html .= '<img src="' . $this->_icon($plugin) . '" style="vertical-align:middle" alt="' . $plugin->getIdentifier() . '" title="' . $plugin->getIdentifier() . '" /> ';
            $html .= $tab . '</span>';
        }

        $html .= '<span class="ZFDebug_span ZFDebug_last clickable" id="ZFDebug_toggler" onclick="ZFDebugSlideBar()">&#171;</span>';

        $html .= '</div>';
        $this->_output($html);
    }

    /**
     * Returns version panel
     *
     * @return string
     */
    protected function _getVersionPanel()
    {
        $panel = '<h4>ZFDebug v' . $this->_version . ' - Extends by library Ip v' . Ip_Version::VERSION . '</h4>';
        return $panel;
    }

    /**
     * Returns path to the specific icon
     *
     * @return string
     */
    protected function _icon($plugin)
    {
        if(method_exists($plugin, 'getIconData'))
            return $plugin->getIconData();

        return parent::_icon($plugin->getIdentifier());
    }

    /**
     * Returns html header for the Debug Bar
     *
     * @return string
     */
    protected function _headerOutput() {
        $collapsed = isset($_COOKIE['ZFDebugCollapsed']) ? $_COOKIE['ZFDebugCollapsed'] : 0;

        if (isset($this->_options['css_path'])) {

            $header = '<link rel="stylesheet" type="text/css" href="' . $this->_options['css_path'] . '/' . $this->_identifier . '.css" />' . PHP_EOL;
        }
        else
        {
            $header = '
                <style type="text/css" media="screen">
                    #ZFDebug_debug { font: 11px/1.4em Lucida Grande, Lucida Sans Unicode, sans-serif; position:fixed; bottom:5px; left:5px; color:#000; z-index: ' . $this->_options['z-index'] . ';}
                    #ZFDebug_debug ol {margin:10px 0px; padding:0 25px}
                    #ZFDebug_debug li {margin:0 0 10px 0;}
                    #ZFDebug_debug .clickable {cursor:pointer}
                    #ZFDebug_toggler { font-weight:bold; background:#BFBFBF; }
                    .ZFDebug_span { border: 1px solid #999; border-right:0px; background:#DFDFDF; padding: 5px 5px; }
                    .ZFDebug_last { border: 1px solid #999; }
                    .ZFDebug_panel { text-align:left; position:absolute;bottom:21px;width:600px; max-height:400px; overflow:auto; display:none; background:#E8E8E8; padding:5px; border: 1px solid #999; }
                    .ZFDebug_panel .pre {font: 11px/1.4em Monaco, Lucida Console, monospace; margin:0 0 0 22px}
                    #ZFDebug_exception { border:1px solid #CD0A0A;display: block; }
                </style>
            ';
        }

        if (isset($this->_options['js_path'])) {
            $header .= '<script language="javascript" type="text/javascript" src="' . $this->_options['js_path'] . '/' . $this->_identifier . '.js" ></script>' . PHP_EOL;
        }else
        {
            $header .= '
                <script type="text/javascript" charset="utf-8">
                if (typeof jQuery == "undefined") {
                    var scriptObj = document.createElement("script");
                    scriptObj.src = "'.$this->_options['jquery_path'].'";
                    scriptObj.type = "text/javascript";
                    var head=document.getElementsByTagName("head")[0];
                    head.insertBefore(scriptObj,head.firstChild);
                }

                var ZFDebugLoad = window.onload;
                window.onload = function(){
                    jQuery.noConflict();
                    ZFDebugCollapsed();
                };
                
                function ZFDebugCollapsed() {
                    if ('.$collapsed.' == 1) {
                        ZFDebugPanel();
                        jQuery("#ZFDebug_toggler").html("&#187;");
                        return jQuery("#ZFDebug_debug").css("left", "-"+parseInt(jQuery("#ZFDebug_debug").outerWidth()-jQuery("#ZFDebug_toggler").outerWidth()+1)+"px");
                    }
                }
                
                function ZFDebugPanel(name) {
                    jQuery(".ZFDebug_panel").each(function(i){
                        if(jQuery(this).css("display") == "block") {
                            jQuery(this).slideUp();
                        } else {
                            if (jQuery(this).attr("id") == name)
                                jQuery(this).slideDown();
                            else
                                jQuery(this).slideUp();
                        }
                    });
                }

                function ZFDebugSlideBar() {
                    if (jQuery("#ZFDebug_debug").position().left > 0) {
                        document.cookie = "ZFDebugCollapsed=1;expires=;path=/";
                        ZFDebugPanel();
                        jQuery("#ZFDebug_toggler").html("&#187;");
                        return jQuery("#ZFDebug_debug").animate({left:"-"+parseInt(jQuery("#ZFDebug_debug").outerWidth()-jQuery("#ZFDebug_toggler").outerWidth()+1)+"px"}, "normal", "swing");
                    } else {
                        document.cookie = "ZFDebugCollapsed=0;expires=;path=/";
                        jQuery("#ZFDebug_toggler").html("&#171;");
                        return jQuery("#ZFDebug_debug").animate({left:"5px"}, "normal", "swing");
                    }
                }

                function ZFDebugToggleElement(name, whenHidden, whenVisible){
                    if(jQuery(name).css("display")=="none"){
                        jQuery(whenVisible).show();
                        jQuery(whenHidden).hide();
                    } else {
                        jQuery(whenVisible).hide();
                        jQuery(whenHidden).show();
                    }
                    jQuery(name).slideToggle();
                }
            </script>
            ';
        }

        return $header;
    }
}