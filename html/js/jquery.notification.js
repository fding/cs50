/*!
 * jQuery Cookie Plugin v1.3
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2011, Klaus Hartl
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.opensource.org/licenses/GPL-2.0
 */
(function ($, document, undefined) {

	$.showmsg=function(message)
	{
        $(".push-notification").html(message);
        $(".push-notification").fadeTo("slow",0.7).delay(3000).fadeOut("slow");
	}

})(jQuery, document);
